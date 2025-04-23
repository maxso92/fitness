<div>
    @if (session('status'))
        <div class="alert alert-success mb-3">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mb-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>Абонемент</th>
                <th>Тип</th>
                <th>Дата начала</th>
                <th>Дата окончания</th>
                <th>Осталось посещений</th>
                <th>Тренер</th>
                <th>Статус</th>
                @auth
                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                        <th>Действия</th>
                    @endif
                @endauth
            </tr>
            </thead>
            <tbody>
            @forelse($subscriptions as $subscription)
                <tr>
                    <td>{{ $subscription->name }}</td>
                    <td>{{ $subscription->type === 'time' ? 'По времени' : 'По посещениям' }}</td>
                    <td>{{ \Carbon\Carbon::parse($subscription->pivot->start_date)->format('d.m.Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($subscription->pivot->end_date)->format('d.m.Y') }}</td>
                    <td>{{ $subscription->pivot->remaining_visits }}</td>
                    <td>{{ $subscription->pivot->trainer_id ? ($subscription->trainer->full_name ?? '-') : '-' }}</td>
                    <td>
                        @if($subscription->pivot->is_active)
                            <span class="badge bg-success">Активен</span>
                        @else
                            <span class="badge bg-secondary">Неактивен</span>
                        @endif
                    </td>
                    @auth
                        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                            <td class="d-flex gap-2">
                                <button
                                    wire:click="toggleSubscriptionStatus({{ $subscription->pivot->id }})"
                                    class="btn btn-sm {{ $subscription->pivot->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                >
                                    {{ $subscription->pivot->is_active ? 'Деактивировать' : 'Активировать' }}
                                </button>
                                <button
                                    wire:click="deleteSubscription({{ $subscription->pivot->id }})"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Вы уверены, что хотите удалить этот абонемент?')"
                                >
                                    Удалить
                                </button>
                            </td>
                        @endif
                    @endauth
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Нет абонементов</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
