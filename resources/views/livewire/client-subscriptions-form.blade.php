<div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="mb-3">
            <label class="form-label">Абонемент*</label>
            <select wire:model="subscriptionId" class="form-select @error('subscriptionId') is-invalid @enderror">
                <option value="">Выберите абонемент</option>
                @foreach($subscriptions as $subscription)
                    <option value="{{ $subscription->id }}">
                        {{ $subscription->name }}
                        @if($subscription->type === 'time' && $subscription->duration_days)
                            ({{ $subscription->duration_days }} дней)
                        @elseif($subscription->type === 'visits' && $subscription->visits_count)
                            ({{ $subscription->visits_count }} посещений)
                        @endif
                    </option>
                @endforeach
            </select>
            @error('subscriptionId') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Дата начала*</label>
                <input type="date" wire:model="startDate" class="form-control @error('startDate') is-invalid @enderror">
                @error('startDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            @if($subscriptionId)
                @php $selectedSub = $subscriptions->find($subscriptionId); @endphp
                @if($selectedSub->type === 'time' && $selectedSub->duration_days)
                    <div class="col-md-6">
                        <label class="form-label">Дата окончания</label>
                        <input type="date" wire:model="endDate" class="form-control @error('endDate') is-invalid @enderror">
                        @error('endDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                @elseif($selectedSub->type === 'visits' && $selectedSub->visits_count)
                    <div class="col-md-6">
                        <label class="form-label">Осталось посещений</label>
                        <input type="number" wire:model="remainingVisits" class="form-control @error('remainingVisits') is-invalid @enderror" min="1">
                        @error('remainingVisits') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                @endif
            @endif
        </div>

        @if($hasTrainerService)
            <div class="mb-3">
                <label class="form-label">Тренер</label>
                <select wire:model="trainerId" class="form-select @error('trainerId') is-invalid @enderror">
                    <option value="">Выберите тренера</option>
                    @foreach($trainers as $trainer)
                        <option value="{{ $trainer->id }}">{{ $trainer->full_name }}</option>
                    @endforeach
                </select>
                @error('trainerId') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        @endif

        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Отмена</button>
            <button type="submit" class="btn btn-outline-primary">
                <span wire:loading wire:target="submit" class="spinner-border spinner-border-sm"></span>
                Добавить абонемент
            </button>
        </div>
    </form>
</div>
