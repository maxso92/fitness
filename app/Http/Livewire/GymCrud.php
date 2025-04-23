<?php

namespace App\Http\Livewire;

use App\Models\Gym;
use Livewire\Component;
use Livewire\WithPagination;

class GymCrud extends BaseComponent
{
    use WithPagination;

    public $title = 'Залы';

    public $itemsPerPage = 25;
    public $totalItems;
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public $gymId, $name, $description, $search = '';
    public $isEditMode = false;
    public $isCreateMode = false;
    public $isDeleteMode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function render()
    {
        $query = Gym::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });

        $this->totalItems = $query->count();

        $gyms = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->itemsPerPage);

        return view('livewire.gym-crud', compact('gyms'));
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->gymId = null;
    }

    public function createGym()
    {
        $this->validate();

        Gym::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->resetForm();
        $this->isCreateMode = false;
    }

    public function editGym($gymId)
    {
        $gym = Gym::findOrFail($gymId);
        $this->gymId = $gym->id;
        $this->name = $gym->name;
        $this->description = $gym->description;
        $this->isEditMode = true;
    }

    public function updateGym()
    {
        $this->validate();

        $gym = Gym::findOrFail($this->gymId);
        $gym->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->resetForm();
        $this->isEditMode = false;
    }

    public function deleteGym($gymId)
    {
        $gym = Gym::findOrFail($gymId);
        $gym->delete();
        $this->isDeleteMode = false;
    }

    public function showDeleteModal($gymId)
    {
        $this->gymId = $gymId;
        $this->isDeleteMode = true;
    }

    public function closeModal()
    {
        $this->isCreateMode = false;
        $this->isEditMode = false;
        $this->isDeleteMode = false;
        $this->resetForm();
    }
}
