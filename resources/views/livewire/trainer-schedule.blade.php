<div>
    <div class="mt-4 mb-3">
        <input type="date" id="training-date" placeholder="Выберите дату" class="form-control" wire:model="date">
    </div>

    <div class="table-responsive table-card table-nowrap">
        <table class="table align-middle table-hover mb-0">
            <thead>
            <tr>
                <th>Дата и время</th>
                <th>Клиенты</th>
                <th>Информация</th>
                <th>Статус</th> <!-- Столбец для отображения статуса -->
                <th>Действия</th> <!-- Столбец для иконок -->
            </tr>
            </thead>
            <tbody>
            @forelse($trainings as $training)
                <tr class="{{ $training->status === 'completed' ? 'table-success' : ($training->status === 'cancelled' ? 'table-danger' : '') }}">
                    <td>{{ \Carbon\Carbon::parse($training->training_at)->format('d.m.Y H:i') }}</td>
                    <td>
                        @if($training->clients->count())
                            <ul class="list-unstyled mb-0">
                                @foreach($training->clients as $client)
                                    <li>
                                        <a href="{{ route('users.view', $client->id) }}">
                                            {{ $client->surname }} {{ $client->name }} {{ $client->patronymic }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            Нет клиентов
                        @endif
                    </td>
                    <td>{{ $training->info ?? '—' }}</td>
                    <td>
                        <!-- Статус тренировки -->
                        @if($training->status == 'completed')
                            <span class="badge bg-success">Проведена</span>
                        @elseif($training->status == 'cancelled')
                            <span class="badge bg-danger">Отменена</span>
                            @if($training->cancel_reason )
                            <div class="mt-2">
                                <strong>Причина отмены:</strong>
                                <p>{{ $training->cancel_reason }}</p>
                            </div>
                            @endif
                        @else
                            <span class="badge bg-warning">Запланирована</span>
                        @endif
                    </td>
                    <td>
                        @if($training->status !== 'completed')
                            <button wire:click="markAsCompleted({{ $training->id }})"
                                    class="btn btn-outline-success btn-outline-sm"
                                    data-tippy-content="Отметить как проведенную">
                                <span class="material-symbols-rounded">check_circle</span>

                            </button>
                        @endif

                        @if($training->status !== 'cancelled')
                            <button wire:click="showCancelModal({{ $training->id }})"
                                    class="btn btn-outline-danger btn-outline-sm"
                                    data-tippy-content="Отменить тренировку">
                                <span class="material-symbols-rounded">cancel</span>

                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Нет тренировок</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{ $trainings->links() }}
    </div>

    <!-- Модалка для подтверждения отмены тренировки -->
    @if($confirmationModal)
        <div class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Подтверждение отмены тренировки</h5>
                        <button type="button" class="btn-outline-close" wire:click="$set('confirmationModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <p>Вы уверены, что хотите отменить тренировку?</p>
                        <textarea wire:model="cancelReason" class="form-control" placeholder="Укажите причину отмены (по желанию)"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('confirmationModal', false)">Отмена</button>
                        <button type="button" class="btn btn-outline-danger" wire:click="cancelTraining">Отменить тренировку</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
