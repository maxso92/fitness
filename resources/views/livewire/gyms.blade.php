<div>
    <!-- CRUD header -->
    @include('livewire.includes.crud.header')

    <div class="content pt-3 px-3 px-lg-6">
        <div class="card">
            <!-- Search header -->
            <div class="row align-items-center mx-3 mr-4 me-3 mt-3">
                {{ $gyms->total() > 0 ? "Показано от {$gyms->firstItem()} до {$gyms->lastItem()} из {$gyms->total()} записей" : "Записи не найдены" }}
            </div>

            <div class="row align-items-center mx-2">
                <div class="col-md-6">
                    <form class="d-flex mt-4 mb-4" wire:submit.prevent="search">
                        <button type="button" class="btn btn-outline-primary d-inline-flex align-items-center mr-3 me-3" wire:click="$set('isCreateMode', true)">
                            <span class="align-middle material-symbols-rounded fs-5 me-1">add</span>Создать
                        </button>
                        <input type="text" wire:model="search" class="form-control me-3" placeholder="Поиск по названию зала...">
                        <button class="btn btn-outline-primary" type="submit">Найти</button>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <div class="form-group d-inline-flex align-items-center">
                        <label for="itemsPerPage" class="me-2 mb-0">Элементов на странице:</label>
                        <select id="itemsPerPage" wire:model="itemsPerPage" class="form-select w-auto">
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="250">250</option>
                            <option value="500">500</option>
                            <option value="{{ $totalItems }}">Все</option>
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
                                    <th wire:click="sortBy('id')" style="cursor: pointer;">
                                        #
                                        @include('includes.sort-icon', ['field' => 'id'])
                                    </th>
                                    <th wire:click="sortBy('created_at')" style="cursor: pointer;">
                                        Дата
                                        @include('includes.sort-icon', ['field' => 'created_at'])
                                    </th>
                                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                                        Название
                                        @include('includes.sort-icon', ['field' => 'name'])
                                    </th>
                                    <th>Описание</th>
                                    <th class="text-end">Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($gyms as $gym)
                                    <tr id="gym_{{ $gym->id }}">
                                        <td>#{{ $gym->id }}</td>
                                        <td>
                                            <small class="text-body-secondary">{{ $gym->created_at->format('d.m.Y') }}</small><br>
                                        </td>
                                        <td>{{ $gym->name }}</td>
                                        <td>{{ $gym->description }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-warning btn-sm" wire:click="editGym({{ $gym->id }})">Редактировать</button>
                                            <button class="btn btn-danger btn-sm" wire:click="showDeleteModal({{ $gym->id }})">Удалить</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Записи не найдены</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            {{ $gyms->links('vendor.livewire.custom-pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модалка создания/редактирования -->
    @if($isCreateMode || $isEditMode)
        <div class="modal fade show" tabindex="-1" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $isEditMode ? 'Редактировать зал' : 'Создать зал' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="{{ $isEditMode ? 'updateGym' : 'createGym' }}">
                            <div class="mb-3">
                                <label for="name" class="form-label">Название</label>
                                <input type="text" class="form-control" wire:model="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Описание</label>
                                <textarea class="form-control" wire:model="description"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="closeModal">Отмена</button>
                                <button type="submit" class="btn btn-primary">{{ $isEditMode ? 'Обновить' : 'Создать' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Модалка удаления -->
    @if($isDeleteMode)
        <div class="modal fade show" tabindex="-1" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Удалить зал</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Вы уверены, что хотите удалить этот зал?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Отмена</button>
                        <button type="button" class="btn btn-danger" wire:click="deleteGym({{ $gymId }})">Удалить</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
