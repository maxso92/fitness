<div>
    <!-- CRUD header -->
    @include('livewire.includes.crud.header')

    <div class="content pt-3 px-3 px-lg-6">
        <div class="card">
            <!-- Search header -->
            <div class="row align-items-center mx-3 mr-4 me-3 mt-3">
                {{ $trainings->total() > 0 ? "Показано от {$trainings->firstItem()} до {$trainings->lastItem()} из {$trainings->total()} записей" : "Записи не найдены" }}
            </div>

            <div class="row align-items-center mx-2">
                <div class="col-md-6">
                    <div class="d-flex mt-4 mb-4">
                        <input type="date" wire:model="date" class="form-control me-3" placeholder="Поиск по дате...">
                        <input type="text" wire:model="trainerSearch" class="form-control me-3" placeholder="Поиск по тренеру...">
                        <input type="text" wire:model="clientSearch" class="form-control me-3" placeholder="Поиск по клиенту...">
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="form-group d-inline-flex align-items-center">
                        <label for="itemsPerPage" class="me-2 mb-0">Элементов на странице:</label>
                        <select id="itemsPerPage" wire:model="itemsPerPage" class="form-select w-auto">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Таблица -->
            <div class="py-3">
                <div class="row">
                    <div class="col">
                        <div class="table-responsive table-card table-nowrap">
                            <table class="table align-middle table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>Дата и время</th>
                                    <th>Тренер</th>
                                    <th>Клиенты</th>
                                    <th>Информация</th>
                                    <th>Статус</th>
                                    <th class="text-end">Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($trainings as $training)
                                    <tr class="{{ $training->status === 'completed' ? 'table-success' : ($training->status === 'cancelled' ? 'table-danger' : '') }}">
                                        <td>{{ \Carbon\Carbon::parse($training->training_at)->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('users.view', $training->trainer_id) }}">
                                                {{ $training->trainer->surname }} {{ $training->trainer->name }} {{ $training->trainer->patronymic ?? '' }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($training->clients->count())
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($training->clients as $client)
                                                        <li>
                                                            <a href="{{ route('users.view', $client->id) }}">
                                                                {{ $client->surname }} {{ $client->name }} {{ $client->patronymic ?? '' }}
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
                                            @if($training->status == 'completed')
                                                <span class="badge bg-success">Проведена</span>
                                            @elseif($training->status == 'cancelled')
                                                <span class="badge bg-danger">Отменена</span>
                                                @if($training->cancel_reason)
                                                    <div class="mt-2">
                                                        <strong>Причина отмены:</strong>
                                                        <p>{{ $training->cancel_reason }}</p>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="badge bg-warning">Запланирована</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end">
                                                @if($training->status !== 'completed')
                                                    <button wire:click="markAsCompleted({{ $training->id }})"
                                                            class="btn btn-outline-success btn-outline-sm me-2"
                                                            title="Отметить как проведенную">
                                                        <span class="material-symbols-rounded">check_circle</span>
                                                    </button>
                                                @endif

                                                @if($training->status !== 'cancelled')
                                                    <button wire:click="showCancelModal({{ $training->id }})"
                                                            class="btn btn-outline-danger btn-outline-sm me-2"
                                                            title="Отменить тренировку">
                                                        <span class="material-symbols-rounded">cancel</span>
                                                    </button>
                                                @endif

                                                @if($training->canDelete(auth()->user()))
                                                    <button wire:click="confirmDelete({{ $training->id }})"
                                                            class="btn btn-outline-danger btn-outline-sm"
                                                            title="Удалить тренировку">
                                                        <span class="material-symbols-rounded">delete</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">Нет тренировок</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            {{ $trainings->links('vendor.livewire.custom-pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <!-- Модальное окно удаления тренировки -->
    @if($deleteModal)
        <div class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Подтверждение удаления</h5>
                        <button type="button" class="btn-outline-close" wire:click="$set('deleteModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <p>Вы уверены, что хотите удалить эту тренировку? Это действие нельзя отменить.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('deleteModal', false)">Отмена</button>
                        <button type="button" class="btn btn-outline-danger" wire:click="deleteTraining">Удалить</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
