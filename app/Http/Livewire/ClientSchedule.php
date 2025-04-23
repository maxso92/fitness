<?php

namespace App\Http\Livewire;

use App\Models\Training;
use Livewire\Component;
use Livewire\WithPagination;

class ClientSchedule extends Component
{
    use WithPagination;

    public $date;
    public $clientId;

    public function mount($clientId)
    {
        $this->clientId = $clientId;
    }

    public function render()
    {
        $query = Training::with(['clients', 'trainer'])
        ->whereHas('clients', function ($q) {
            $q->where('users.id', $this->clientId);
        });

        if ($this->date) {
            $query->whereDate('training_at', $this->date);
        }

        return view('livewire.client-schedule', [
            'trainings' => $query->orderBy('training_at')->paginate(10),
        ]);
    }

}
