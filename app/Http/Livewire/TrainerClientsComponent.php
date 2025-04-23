<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class TrainerClientsComponent extends Component
{
    use WithPagination;

    public $trainerId;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage(); // сбрасываем на первую страницу при поиске
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';

        $clients = User::where('trainer_id', $this->trainerId)
            ->where(function ($query) use ($searchTerm) {
                $query->whereRaw("CONCAT(surname, ' ', name, ' ', patronymic) LIKE ?", [$searchTerm])
                    ->orWhere('surname', 'like', $searchTerm)
                    ->orWhere('name', 'like', $searchTerm)
                    ->orWhere('patronymic', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            })
            ->orderBy('surname')
            ->paginate(10);

        return view('livewire.trainer-clients-component', compact('clients'));
    }

}
