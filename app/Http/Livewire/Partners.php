<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Partners extends BaseComponent
{
    use WithPagination;

    public $confirmingUserDeletion = false;
    public $userToDelete;
    public $search;
    public $itemsPerPage = 25;
    public $totalItems;

    public $searchCriteriaCount = 0;
    public $searchFormVisible = false;
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public $role;
    public $gym_id;
    public $created_at_from;
    public $created_at_to;
    public $email;
    public $birthday;
    public $status;
    public $showDeleted = false;

    public $title = 'Пользователи';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {

    }

    public function updateSearchCriteriaCount()
    {
        $this->searchCriteriaCount = collect([
            $this->email,
            $this->role,
            $this->gym_id,
            $this->birthday,
            $this->status,
            $this->showDeleted
        ])->filter()->count();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function updatedItemsPerPage($value)
    {
        $this->resetPage();
    }

    public function render()
    {
        $title = $this->title;
        $gyms = \App\Models\Gym::all();
        $currentUser = Auth::user();

        $query = User::query()
            ->when($this->created_at_from && $this->created_at_to, function ($query) {
                $from = date('Y-m-d 00:00:00', strtotime($this->created_at_from));
                $to = date('Y-m-d 23:59:59', strtotime($this->created_at_to));
                $query->whereBetween('created_at', [$from, $to]);
            })
            ->when($this->birthday, function ($query) {
                $query->whereDate('birthday', $this->birthday);
            })
            ->when($this->email, function ($query) {
                $query->where('email', 'LIKE', '%'.$this->email.'%');
            })
            ->when($this->role, function ($query) {
                $query->where('role', $this->role);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->gym_id !== null, function ($query) {
                $query->where('gym_id', $this->gym_id);
            })
            ->when($this->showDeleted, function ($query) {
                $query->where('isDeleted', 1);
            })
            ->when($currentUser->role === 'manager', function ($query) use ($currentUser) {
                // Managers can only see trainers, clients, and themselves
                $query->where(function($q) use ($currentUser) {
                    $q->whereIn('role', ['trainer', 'client'])
                        ->orWhere('id', $currentUser->id);
                });
            })
            ->when($this->search, function ($query) {
                $searchTerms = explode(' ', $this->search);
                $query->where(function($q) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $q->where(function($subQuery) use ($term) {
                            $subQuery->where('surname', 'LIKE', '%'.$term.'%')
                                ->orWhere('name', 'LIKE', '%'.$term.'%')
                                ->orWhere('patronymic', 'LIKE', '%'.$term.'%')
                                ->orWhere('email', 'LIKE', '%'.$term.'%')
                                ->orWhereRaw("CONCAT(surname, ' ', name, ' ', patronymic) LIKE ?", ['%'.$term.'%']);
                        });
                    }
                });
            });

        $this->totalItems = $query->count();

        $users = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->itemsPerPage);

        $this->updateSearchCriteriaCount();

        return view('livewire.partners', compact('title', 'users', 'gyms', 'currentUser'));
    }

    public function confirmUserDeletion(User $user)
    {
        $currentUser = Auth::user();

        // Check permissions
        if ($currentUser->role === 'manager') {
            if ($user->role === 'admin' || ($user->role === 'manager' && $user->id !== $currentUser->id)) {
                $this->emit('showToast', 'error', 'У вас нет прав для удаления этого пользователя');
                return;
            }
        }

        $this->userToDelete = $user;
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser()
    {
        try {
            $this->userToDelete->delete();
            $this->emit('showToast', 'success', 'Пользователь успешно удален!');
            $this->confirm('refreshComponent');
        } catch (\Exception $e) {
            $this->emit('showToast', 'error', 'Ошибка при удалении: '.$e->getMessage());
        } finally {
            $this->confirmingUserDeletion = false;
        }
    }

    public function search()
    {
        $this->resetPage();
    }

    public function canEditUser($user)
    {
        $currentUser = Auth::user();

        if ($currentUser->role === 'admin') {
            return true;
        }

        if ($currentUser->role === 'manager') {
            return $user->role !== 'admin' &&
                ($user->role !== 'manager' || $user->id === $currentUser->id);
        }

        return false;
    }
}
