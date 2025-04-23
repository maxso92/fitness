<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PaginationLinks extends Component
{
    public function render()
    {
        return view('livewire.pagination-links');
    }

    public function gotoPage($page)
    {
        $this->emit('gotoPage', $page);
    }

}
