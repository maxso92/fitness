<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class ClientSubscriptions extends Component
{
    public $clientId;
    public $subscriptions;

    protected $listeners = ['subscriptionAdded' => 'loadSubscriptions'];

    public function mount($clientId)
    {
        $this->clientId = $clientId;
        $this->loadSubscriptions();
    }

    public function loadSubscriptions()
    {
        $this->subscriptions = User::findOrFail($this->clientId)
            ->subscriptions()
            ->withPivot(['id', 'start_date', 'end_date', 'remaining_visits', 'is_active', 'trainer_id'])
            ->orderByPivot('is_active', 'desc')
            ->orderByPivot('end_date', 'desc')
            ->get();
    }

    public function toggleSubscriptionStatus($subscriptionPivotId)
    {
        $user = User::findOrFail($this->clientId);

        $pivot = $user->subscriptions()->wherePivot('id', $subscriptionPivotId)->first()->pivot;

        if (!$pivot) {
            session()->flash('error', 'Абонемент не найден.');
            return;
        }

        $isActive = !$pivot->is_active;

        $user->subscriptions()->wherePivot('id', $subscriptionPivotId)->updateExistingPivot($pivot->subscription_id, [
            'is_active' => $isActive,
            'updated_at' => now()
        ]);

        $this->loadSubscriptions();
        session()->flash('status', $isActive ? 'Абонемент активирован' : 'Абонемент деактивирован');
    }

    public function deleteSubscription($subscriptionPivotId)
    {
        $user = User::findOrFail($this->clientId);

        $user->subscriptions()->wherePivot('id', $subscriptionPivotId)->detach();

        $this->loadSubscriptions();
        session()->flash('status', 'Абонемент успешно удален');
    }

    public function render()
    {
        return view('livewire.client-subscriptions');
    }
}
