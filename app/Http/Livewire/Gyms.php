<?php

namespace App\Http\Livewire;

use App\Models\Gym;
use Livewire\Component;
use Livewire\WithPagination;

class Gyms extends Component
{
    use WithPagination;

    public $search;
    public $itemsPerPage = 25;
    public $totalItems;
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public $title = 'Залы';

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
        $gyms = Gym::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'LIKE', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->itemsPerPage);

        $this->totalItems = $gyms->total();

        return view('livewire.gyms', compact('gyms'));
    }

    public function search()
    {
        $this->resetPage();
    }
}
