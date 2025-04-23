<?php

namespace App\Http\Livewire;

use App\Models\Subscription;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class SubscriptionCrud extends BaseComponent
{
    use WithPagination;

    public $title = 'Абонементы';
    public $itemsPerPage = 25;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $search = '';

    public $subscriptionId;
    public $name;
    public $description;
    public $type = 'time';
    public $duration_days;
    public $visits_count;
    public $has_trainer_service = false;
    public $trainer_id;
    public $is_active = true;

    public $isCreateMode = false;
    public $isEditMode = false;
    public $isDeleteMode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|in:time,visits',
        'duration_days' => 'required_if:type,time|integer|min:1|nullable',
        'visits_count' => 'required_if:type,visits|integer|min:1|nullable',
        'has_trainer_service' => 'boolean',
        'trainer_id' => 'required_if:has_trainer_service,true|exists:users,id|nullable',
        'is_active' => 'boolean'
    ];

    public function updated($propertyName)
    {
        // Сбрасываем валидацию при изменении типа
        if ($propertyName === 'type') {
            $this->resetValidation([
                'duration_days',
                'visits_count',
                'trainer_id'
            ]);
        }

        // Сбрасываем валидацию тренера при отключении услуги
        if ($propertyName === 'has_trainer_service' && !$this->has_trainer_service) {
            $this->resetValidation(['trainer_id']);
            $this->trainer_id = null;
        }
    }
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $query = Subscription::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });

        $subscriptions = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->itemsPerPage);

        $trainers = User::where('role', 'trainer')->get();

        return view('livewire.subscription-crud', compact('subscriptions', 'trainers'));
    }

    public function createSubscription()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'is_active' => $this->is_active,
        ];

        // Добавляем поля в зависимости от типа
        if ($this->type === 'time') {
            $data['duration_days'] = $this->duration_days;
            $data['visits_count'] = null;
        } else {
            $data['visits_count'] = $this->visits_count;
            $data['duration_days'] = null;
        }

        // Добавляем данные тренера если услуга активна
        if ($this->has_trainer_service) {
            $data['has_trainer_service'] = true;
            $data['trainer_id'] = $this->trainer_id;
        } else {
            $data['has_trainer_service'] = false;
            $data['trainer_id'] = null;
        }

        try {
            Subscription::create($data);
            session()->flash('message', 'Абонемент успешно создан!');
            $this->resetForm();
            $this->isCreateMode = false;
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка: '.$e->getMessage());
        }
    }

    public function editSubscription($subscriptionId)
    {
        $subscription = Subscription::findOrFail($subscriptionId);
        $this->subscriptionId = $subscription->id;
        $this->name = $subscription->name;
        $this->description = $subscription->description;
        $this->type = $subscription->type;
        $this->duration_days = $subscription->duration_days;
        $this->visits_count = $subscription->visits_count;
        $this->has_trainer_service = $subscription->has_trainer_service;
        $this->trainer_id = $subscription->trainer_id;
        $this->is_active = $subscription->is_active;
        $this->isEditMode = true;
    }

    public function updateSubscription()
    {
        $this->validate();

        $subscription = Subscription::findOrFail($this->subscriptionId);
        $subscription->update([
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'duration_days' => $this->type === 'time' ? $this->duration_days : null,
            'visits_count' => $this->type === 'visits' ? $this->visits_count : null,
            'has_trainer_service' => $this->has_trainer_service,
            'trainer_id' => $this->has_trainer_service ? $this->trainer_id : null,
            'is_active' => $this->is_active,
        ]);

        $this->resetForm();
        $this->isEditMode = false;
    }

    public function deleteSubscription($subscriptionId)
    {
        Subscription::findOrFail($subscriptionId)->delete();
        $this->isDeleteMode = false;
    }

    public function showDeleteModal($subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
        $this->isDeleteMode = true;
    }

    public function closeModal()
    {
        $this->isCreateMode = false;
        $this->isEditMode = false;
        $this->isDeleteMode = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'subscriptionId',
            'name',
            'description',
            'type',
            'duration_days',
            'visits_count',
            'has_trainer_service',
            'trainer_id',
            'is_active'
        ]);
    }
}
