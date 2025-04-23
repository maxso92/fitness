<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Visit;

class VisitHistoryComponent extends Component
{
    use WithPagination;

    public $userId;

    public function mount($userId)
    {
        $this->userId = $userId;
    }

    public function render()
    {
        $visits = Visit::where('user_id', $this->userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.visit-history-component', [
            'visits' => $visits
        ]);
    }
}
