<?php

namespace App\Http\Livewire;

use App\Models\Subscription;
use App\Models\Training;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class ClubStatistics extends BaseComponent
{
    public $timePeriod = 'day'; // day, month, quarter, year
    public $totalClients;
    public $totalSubscriptions;
    public $trainingsCount;
    public $activeSubscriptions;

    protected $listeners = ['timePeriodChanged' => 'changeTimePeriod'];

    public function mount()
    {
        $this->loadStatistics();
    }

    public function changeTimePeriod($period)
    {
        $this->timePeriod = $period;
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $now = Carbon::now();

        // Set date range based on selected period
        switch ($this->timePeriod) {
            case 'month':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                break;
            case 'quarter':
                $startDate = $now->copy()->startOfQuarter();
                $endDate = $now->copy()->endOfQuarter();
                break;
            case 'year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                break;
            case 'day':
            default:
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
        }


        $this->totalClients = User::where('role', 'client')->count();
        $this->totalSubscriptions = Subscription::count();

        $this->activeSubscriptions = \DB::table('client_subscriptions')
            ->where('is_active', true)
            ->count();

        // Get trainings count for the period
        $this->trainingsCount = Training::where('status', 'completed')
            ->whereBetween('training_at', [$startDate, $endDate])
            ->count();
    }

    public function render()
    {
        return view('livewire.club-statistics');
    }
}
