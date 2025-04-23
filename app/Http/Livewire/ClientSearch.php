<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class ClientSearch extends Component
{
    public $search = '';

    public function render()
    {
        $clients = User::where('role', 'client')
            ->where(function ($query) {
                $query->whereRaw("CONCAT(surname, ' ', name, ' ', patronymic) LIKE ?", ['%' . $this->search . '%']);
            })
            ->get();

        return view('livewire.client-search', [
            'clients' => $clients
        ]);
    }
}
