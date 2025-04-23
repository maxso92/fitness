<div>
    <!-- CRUD header -->
    @include('livewire.includes.crud.header')

    <!-- Search filter -->
    @include('livewire.includes.crud.search-header')
    <div class="row">
        <div class="col-sm-12 col-md-4 mb-3">
            <div class="form-group">
                <label for="role" class="form-label">Роль</label>
                <select name="role" id="role" wire:model.defer="role" class="form-control">
                    <option value="">-- Показать все --</option>
                    <option value="client">Клиент</option>
                    <option value="trainer">Тренер</option>
                    @if(auth()->user()->role === 'admin')
                        <option value="manager">Менеджер</option>
                        <option value="admin">Администратор</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-4 mb-3">
            <div class="form-group">
                <label for="status" class="form-label">Статус</label>
                <select name="status" id="status" wire:model.defer="status" class="form-control">
                    <option value="">-- Показать все --</option>
                    <option value="active">Активный</option>
                    <option value="inactive">Неактивный</option>
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-4 mb-3">
            <div class="form-check mt-4 pt-2">
                <input type="checkbox" class="form-check-input" id="showDeleted" wire:model.defer="showDeleted">
                <label class="form-check-label" for="showDeleted">Показать удаленных</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-4 mb-3">
            <div class="form-group">
                <label for="created_at_from" class="form-label">Дата создания от</label>
                <input type="date" name="created_at_from" id="created_at_from" wire:model.defer="created_at_from" class="form-control">
            </div>
        </div>
        <div class="col-sm-12 col-md-4 mb-3">
            <div class="form-group">
                <label for="created_at_to" class="form-label">Дата создания до</label>
                <input type="date" name="created_at_to" id="created_at_to" wire:model.defer="created_at_to" class="form-control">
            </div>
        </div>
        <div class="col-sm-12 col-md-4 mb-3">
            <div class="form-group">
                <label for="birthday" class="form-label">Дата рождения</label>
                <input type="date" name="birthday" id="birthday" wire:model.defer="birthday" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-4 mb-3">
            <label for="name" class="form-label">E-mail</label>
            <input type="text" name="email" id="name" wire:model.defer="email" class="form-control" placeholder="Email">
        </div>
        <div class="col-sm-12 col-md-4 mb-3">
            <label for="gym_id" class="form-label">Зал</label>
            <div wire:ignore>
                <select id="gym_id" name="gym_id" wire:model.defer="gym_id" data-choices='{"searchEnabled":true, "itemSelectText":""}' class="form-control">
                    <option value="">Не выбрано</option>
                    <option value="0">Все залы (общие)</option>
                    @foreach ($gyms as $gym)
                        <option value="{{ $gym->id }}">{{ $gym->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @include('livewire.includes.crud.search-footer')

    <div class="row align-items-center mx-3 mr-4 me-3">
        {{ $users->total() > 0 ? "Показано от {$users->firstItem()} до {$users->lastItem()} из {$users->total()} записей" : "Записи не найдено" }}
    </div>

    <div class="row align-items-center mx-2">
        <div class="col-md-6">
            <form class="d-flex mt-4 mb-4" wire:submit.prevent="search">
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                    <a href="{{ route('users.create') }}" class="btn btn-outline-primary d-inline-flex align-items-center mr-3 me-3">
                        <span class="align-middle material-symbols-rounded fs-5 me-1">add</span>Создать
                    </a>
                @endif
                <input type="text" wire:model.defer="search" class="form-control me-3" placeholder="Поиск по ФИО или email...">
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
                            <th>ФИО</th>
                            <th>Роль</th>
                            <th>Зал</th>
                            <th class="text-end">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($users as $user)
                            <tr id="user_{{ $user->id }}">
                                <td>#{{ $user->id }}</td>
                                <td>
                                    <small class="text-body-secondary">{{ $user->created_at->format('d.m.Y') }}</small><br>
                                    <small class="text-body-secondary">{{ $user->updated_at->format('d.m.Y') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="{{ $user->avatar_url }}" alt="Аватар" class="avatar sm rounded-circle">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">
                                                <a href="{{ route('users.view', $user->id) }}" class="text-decoration-none text-dark hover-text-primary">
                                                    {{ $user->surname }} {{ $user->name }} {{ $user->patronymic }}
                                                </a>
                                            </h6>
                                            <small class="text-body-secondary">
                                                <a href="{{ route('users.view', $user->id) }}" class="text-decoration-none">{{ $user->email }}</a>
                                            </small>
                                            <br>
                                            @if($user->isDeleted)
                                                <span class="badge bg-secondary">Удален</span>
                                            @else
                                                @include('livewire.includes.crud.statusBadge', ['field' => $user->status])
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @switch($user->role)
                                        @case('client') Клиент @break
                                        @case('trainer') Тренер @break
                                        @case('manager') Менеджер @break
                                        @case('admin') Администратор @break
                                        @default {{ $user->role }}
                                    @endswitch
                                </td>

                                <td>
                                    {{ $user->gym_id === 0 ? 'Все залы' : ($user->gym->name ?? 'Не указан') }}
                                    @if($user->trainer)
                                        <br><small class="text-body-secondary">Тренер: {{ $user->trainer->surname }} {{ $user->trainer->name }} {{ $user->trainer->patronymic }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end align-items-center">
                                        @if($this->canEditUser($user))
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-success btn-outline-sm" data-tippy-content="Редактирование">
                                                <span class="material-symbols-rounded align-middle fs-5 text-body">edit</span>
                                            </a>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Записи не найдены</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $users->links('vendor.livewire.custom-pagination') }}
                </div>
            </div>
        </div>
    </div>

    @include('livewire.includes.crud.deleteModal')
    @include('livewire.includes.crud.scripts')
</div>
