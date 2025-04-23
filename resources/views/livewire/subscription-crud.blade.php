<div>
    @include('livewire.includes.crud.header')

    <div class="content pt-3 px-3 px-lg-6">
        <div class="card">
            <div class="row align-items-center mx-3 mr-4 me-3 mt-3">
                {{ $subscriptions->total() > 0 ? "Показано от {$subscriptions->firstItem()} до {$subscriptions->lastItem()} из {$subscriptions->total()} записей" : "Записи не найдены" }}
            </div>

            <div class="row align-items-center mx-2">
                <div class="col-md-6">
                    <form class="d-flex mt-4 mb-4" wire:submit.prevent="search">
                        <button type="button" class="btn btn-outline-primary d-inline-flex align-items-center mr-3 me-3" wire:click="$set('isCreateMode', true)">
                            <span class="align-middle material-symbols-rounded fs-5 me-1">add</span>Создать
                        </button>
                        <input type="text" wire:model="search" class="form-control me-3" placeholder="Поиск по названию...">
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <div class="form-group d-inline-flex align-items-center">
                        <label for="itemsPerPage" class="me-2 mb-0">Элементов на странице:</label>
                        <select id="itemsPerPage" wire:model="itemsPerPage" class="form-select w-auto">
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="py-3">
                <div class="row">
                    <div class="col">
                        <div class="table-responsive table-card table-nowrap">
                            <table class="table align-middle table-hover mb-0">
                                <thead>
                                <tr>
                                    <th wire:click="sortBy('id')" style="cursor: pointer;">
                                        #
                                        @include('includes.sort-icon', ['field' => 'id'])
                                    </th>
                                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                                        Название
                                        @include('includes.sort-icon', ['field' => 'name'])
                                    </th>
                                    <th>Тип</th>
                                    <th>Длительность/Посещения</th>
                                    <th>Доп. услуги</th>
                                    <th wire:click="sortBy('is_active')" style="cursor: pointer;">
                                        Статус
                                        @include('includes.sort-icon', ['field' => 'is_active'])
                                    </th>
                                    <th class="text-end">Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($subscriptions as $subscription)
                                    <tr>
                                        <td>#{{ $subscription->id }}</td>
                                        <td>{{ $subscription->name }}</td>
                                        <td>{{ $subscription->type_name }}</td>
                                        <td>{{ $subscription->duration }}</td>
                                        <td>
                                            @if($subscription->has_trainer_service)
                                                <span class="badge bg-primary">Тренер: {{ $subscription->trainer->full_name ?? 'Не назначен' }}</span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if($subscription->is_active)
                                                <span class="badge bg-success">Активен</span>
                                            @else
                                                <span class="badge bg-danger">Неактивен</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-outline-warning btn-outline-sm" wire:click="editSubscription({{ $subscription->id }})" title="Редактировать">
                                                <span class="material-symbols-rounded">edit</span>
                                            </button>
                                            <button class="btn btn-outline-danger btn-outline-sm" wire:click="showDeleteModal({{ $subscription->id }})" title="Удалить">
                                                <span class="material-symbols-rounded">delete</span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">Записи не найдены</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            {{ $subscriptions->links('vendor.livewire.custom-pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($isCreateMode || $isEditMode)
        <div class="modal fade show" tabindex="-1" style="display: block;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $isEditMode ? 'Редактировать абонемент' : 'Создать абонемент' }}</h5>
                        <button type="button" class="btn-outline-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="{{ $isEditMode ? 'updateSubscription' : 'createSubscription' }}">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Название*</label>
                                    <input type="text" class="form-control" wire:model="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Тип абонемента*</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" wire:model="type" id="type_time" value="time">
                                        <label class="form-check-label" for="type_time">
                                            По времени
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" wire:model="type" id="type_visits" value="visits">
                                        <label class="form-check-label" for="type_visits">
                                            По количеству посещений
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    @if($type === 'time')
                                        <label for="duration_days" class="form-label">Длительность (дней)*</label>
                                        <input type="number" class="form-control" wire:model="duration_days" min="1" required>
                                    @else
                                        <label for="visits_count" class="form-label">Количество посещений*</label>
                                        <input type="number" class="form-control" wire:model="visits_count" min="1" required>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="has_trainer_service" id="has_trainer_service">
                                        <label class="form-check-label" for="has_trainer_service">Услуга тренера</label>
                                    </div>
                                    @if($has_trainer_service)
                                        <label for="trainer_id" class="form-label mt-2">Тренер*</label>
                                        <select class="form-select" wire:model="trainer_id" required>
                                            <option value="">Выберите тренера</option>
                                            @foreach($trainers as $trainer)
                                                <option value="{{ $trainer->id }}">{{ $trainer->full_name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Описание</label>
                                <textarea class="form-control" wire:model="description" rows="3"></textarea>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" wire:model="is_active" id="is_active">
                                <label class="form-check-label" for="is_active">Активен</label>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" wire:click="closeModal">Отмена</button>
                                <button type="submit" class="btn btn-outline-primary">{{ $isEditMode ? 'Обновить' : 'Создать' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($isDeleteMode)
        <div class="modal fade show" tabindex="-1" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Удалить абонемент</h5>
                        <button type="button" class="btn-outline-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Вы уверены, что хотите удалить этот абонемент?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeModal">Отмена</button>
                        <button type="button" class="btn btn-outline-danger" wire:click="deleteSubscription({{ $subscriptionId }})">Удалить</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
