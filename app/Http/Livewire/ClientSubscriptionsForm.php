<?php

namespace App\Http\Livewire;

use App\Models\Subscription;
use App\Models\User;
use Livewire\Component;

class ClientSubscriptionsForm extends Component
{
    public $clientId;
    public $subscriptionId;
    public $startDate;
    public $endDate;
    public $remainingVisits;
    public $subscriptions;
    public $hasTrainerService = false;
    public $trainerId;
    public $trainers = [];

    protected $rules = [
        'subscriptionId' => 'required|exists:subscriptions,id',
        'startDate' => 'required|date',
        'endDate' => 'nullable|date|after_or_equal:startDate',
        'remainingVisits' => 'nullable|integer|min:1',
        'trainerId' => 'nullable|required_if:hasTrainerService,true|exists:users,id'
    ];

    public function mount($clientId)
    {
        $this->clientId = $clientId;
        $this->startDate = now()->format('Y-m-d');
        $this->loadSubscriptions();
    }

    public function loadSubscriptions()
    {
        $this->subscriptions = Subscription::where('is_active', true)->get();
        $this->trainers = User::where('role', 'trainer')->get();
    }

    public function updatedSubscriptionId($value)
    {
        if ($value) {
            $subscription = Subscription::find($value);
            $this->hasTrainerService = $subscription->has_trainer_service ?? false;

            if ($subscription->type === 'time') {
                $this->endDate = $subscription->duration_days
                    ? now()->addDays($subscription->duration_days)->format('Y-m-d')
                    : null;
                $this->remainingVisits = null;
            } else {
                $this->remainingVisits = $subscription->visits_count ?? null;
                $this->endDate = null;
            }
        }
    }

    public function submit()
    {
        $this->validate();

        try {
            $subscription = Subscription::findOrFail($this->subscriptionId);

            $pivotData = [
                'start_date' => $this->startDate,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Добавляем end_date только если он указан и абонемент временной
            if ($subscription->type === 'time' && $this->endDate) {
                $pivotData['end_date'] = $this->endDate;
            }

            // Добавляем remaining_visits только если абонемент по посещениям
            if ($subscription->type === 'visits' && $this->remainingVisits) {
                $pivotData['remaining_visits'] = $this->remainingVisits;
            }

            // Добавляем тренера только если требуется
            if ($this->hasTrainerService && $this->trainerId) {
                $pivotData['trainer_id'] = $this->trainerId;
            }

            User::findOrFail($this->clientId)
                ->subscriptions()
                ->attach($subscription->id, $pivotData);

            $this->emit('subscriptionAdded');
            $this->dispatchBrowserEvent('close-modal');
            session()->flash('success', 'Абонемент успешно добавлен!');

            $this->resetForm();

        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при добавлении абонемента: '.$e->getMessage());
        }
    }

    protected function resetForm()
    {
        $this->reset([
            'subscriptionId',
            'startDate',
            'endDate',
            'remainingVisits',
            'trainerId',
            'hasTrainerService'
        ]);
        $this->startDate = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.client-subscriptions-form');
    }
}
