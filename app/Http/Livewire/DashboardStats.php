<?php

// app/Http/Livewire/DashboardStats.php

// app/Http/Livewire/DashboardStats.php

namespace App\Http\Livewire;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardStats extends BaseComponent
{
    public $newUsersToday;
    public $totalUsers;

    public $paymentAmountToday;
    public $totalPaymentAmount;
    public $newVisitors;
    public $newVisits;



    public function mount()
    {
        // Fetch data initially
        $this->getDataForCustomPeriod();
    }

    public function render()
    {
        $user = Auth::user();
        $trainers = User::where('role', 'trainer')->get();
        $gyms = \App\Models\Gym::all();

        return view('livewire.dashboard-stats', [
            'user' => $user,
            'trainers' => $trainers,
            'gyms' => $gyms,
        ]);
    }

    public function getDataForCustomPeriod()
    {



        $today = today();

        $this->newUsersToday = User::whereDate('created_at', $today)->count();
        $this->totalUsers = User::count();

        $this->newVisitors = '0';
        $this->newVisits = '0';



        $this->paymentAmountToday = 0;
        $this->totalPaymentAmount = 0;
    }
}
