<?php

namespace App\Http\Livewire;

use App\Models\AuthLog;
use Livewire\Component;
use Livewire\WithPagination;

class AuthHistoryComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $sortBy = 'id';  // definišite polje za početno sortiranje
    public $sortDirection = 'desc'; // početan smjer sortiranja

    public function sortBy($field)
    {
        if ($this->sortDirection =='asc') {
            $this->sortDirection = 'desc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortBy = $field;
    }

    public function mount($userId = null)
    {
        $this->userId = $userId;
        $this->activeTab = 'auth';
    }

    public function render()
    {
       // $authHistory = AuthLog::where('user_id', $id)->get(); // replace AuthLog with your auth history model

        return view('livewire.auth-history-component', [
            'authHistory' => AuthLog::where('user_id', $this->userId)->orderBy($this->sortBy, $this->sortDirection)->paginate(10),
        ]);
    }
}
