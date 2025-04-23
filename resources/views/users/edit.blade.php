<x-app-layout>
    @php($route = 'partners')

    @include('livewire.includes.crud.card-edit-header', ['title' => 'Пользователи' , 'actions' => 'редактирование', 'route' => $route, 'id' => $user->id])



    <div class="pt-3 px-3 px-lg-6 overflow-hidden">



        <div class="row align-items-lg-end align-items-center mx-lg-0 mb-4 mb-lg-0">
            <div class="col-auto">
                <!-- Avatar -->
                <div class="avatar xxl rounded-4 p-1 bg-body overflow-hidden position-relative mt-lg-n5">
                    <img src="{{ $user->avatar_url }}" alt="Аватар" class="img-fluid rounded-4">
                </div>
            </div>


            <div class="col mb-3">
                <div class="row align-items-md-end">
                    <div class="col-12 col-md">
                        <!-- ФИО -->
                        <h3 class="profile-title mb-1" style="font-size: 28px !important;">
                            {{ $user->surname }} {{ $user->name }} {{ $user->patronymic }}
                        </h3>

                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="content pt-3 px-3 px-lg-6">
        <div class="row">
            <div class="col-12 col-lg-8 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <form id="form" method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">


                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="avatar" class="form-label">Фото (аватар):</label>
                                        <input type="file" name="avatar" class="form-control" accept="image/*">

                                        @error('avatar')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>



                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="surname" class="form-label">Фамилия:</label>
                                        <input type="text" name="surname" value="{{ $user->surname }}" class="form-control" required>
                                        @error('surname')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="name" class="form-label">Имя:</label>
                                        <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                                        @error('name')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="patronymic" class="form-label">Отчество:</label>
                                        <input type="text" name="patronymic" value="{{ $user->patronymic }}" class="form-control">
                                        @error('patronymic')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="role" class="form-label">Роль:</label>
                                        @php($currentRole = auth()->user()->role)

                                        <select name="role" id="roleSelect" class="form-control">
                                            @if($currentRole === 'admin')
                                                <option value="admin" @selected($user->role === 'admin')>Администратор</option>
                                                <option value="manager" @selected($user->role === 'manager')>Менеджер</option>
                                            @endif

                                            @if(in_array($currentRole, ['admin', 'manager']))
                                                <option value="trainer" @selected($user->role === 'trainer')>Тренер</option>
                                                <option value="client" @selected($user->role === 'client')>Клиент</option>
                                            @endif
                                        </select>
                                        @error('role')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                                        @error('email')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="birthday" class="form-label">Дата рождения:</label>
                                        <input type="date" name="birthday" value="{{ $user->birthday }}" class="form-control">
                                        @error('birthday')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="password" class="form-label">Новый пароль:</label>
                                        <input type="password" name="password" autocomplete="new-password" class="form-control">
                                        @error('password')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="gym_id" class="form-label">Зал:</label>
                                        <select name="gym_id" class="form-control">
                                            <option value="">-- Не выбрано --</option>
                                            <option value="0" @if($user->gym_id == 0) selected @endif>Все залы</option>
                                            @foreach(App\Models\Gym::all() as $gym)
                                                <option value="{{ $gym->id }}" @if($user->gym_id == $gym->id) selected @endif>{{ $gym->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('gym_id')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="status" class="form-label">Статус:</label>
                                        <select name="status" class="form-control">
                                            <option value="active" @if($user->status == 'active') selected @endif>Активный</option>
                                            <option value="inactive" @if($user->status == 'inactive') selected @endif>Неактивный</option>
                                        </select>
                                        @error('status')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Динамическое поле для тренера -->
                                    <div class="col-sm-12 col-xl-12 mb-3" id="trainer_select_block" style="display: none;">
                                        <label for="trainer_id" class="form-label">Тренер:</label>
                                        <select name="trainer_id" class="form-control">
                                            <option value="">Выберите тренера</option>
                                            @foreach(App\Models\User::where('role', 'trainer')->get() as $trainer)
                                                <option value="{{ $trainer->id }}" @if($user->trainer_id == $trainer->id) selected @endif>
                                                    {{ $trainer->surname }} {{ $trainer->name }} {{ $trainer->patronymic }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('trainer_id')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Информационное поле -->
                                <div class="row">
                                    <div class="col-sm-12 col-xl-12 mb-3" id="info_block">
                                        <label for="information" id="info_label" class="form-label">Информация:</label>
                                        <textarea name="information" rows="5" class="form-control">{{ old('information', $user->information) }}</textarea>
                                        @error('information')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- КНОПКИ УДАЛЕНЫ ОТСЮДА -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Правая колонка с кнопками -->
            <div class="col-12 col-lg-4 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                        <button form="form" type="submit" class="btn btn-outline-primary ">Сохранить</button>

                            <a href="{{ route('users.view', $user->id) }}" class="btn btn-outline-primary">Просмотр профиля</a>
                            @can('admin', 'moderator')
                            @if($user->isDeleted)
                                <!-- Кнопка восстановления -->
                                <button type="button" class="btn btn-outline-success"
                                        onclick="document.getElementById('restore-form').submit()">
                                    Восстановить из удаленных
                                </button>
                                <form id="restore-form" action="{{ route('users.restore', $user->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('PUT')
                                </form>
                            @else

                                <!-- Кнопка мягкого удаления -->
                                <button type="button" class="btn btn-outline-danger"
                                        data-bs-toggle="modal" data-bs-target="#confirmSoftDeleteModal">
                                    Удалить
                                </button>

                            @endif
                            @endcan
                            <!-- Кнопка полного удаления (только для админа) -->
                            @can('admin')
                                <button type="button" class="btn btn-outline-danger"
                                        data-bs-toggle="modal" data-bs-target="#confirmForceDeleteModal">
                                    Удалить безвозвратно
                                </button>
                            @endcan

                            @can('admin', 'moderator')
                                <button type="button" class="btn {{$user->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success'}} "
                                        data-bs-toggle="modal" data-bs-target="#confirmBlockModal">
                                    {{$user->status === 'active' ? 'Заблокировать' : 'Разблокировать'}}
                                </button>
                            @endcan

                            <a href="{{ route('partners') }}" class="btn btn-outline-secondary mb-2">Назад</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно мягкого удаления -->
    <div class="modal fade" id="confirmSoftDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Удалить пользователя?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Пользователь будет перемещен в архив. Вы сможете восстановить его позже.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                    <form action="{{ route('users.softDelete', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Удалить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно полного удаления -->
    <div class="modal fade" id="confirmForceDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Удалить пользователя безвозвратно?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Это действие невозможно отменить. Все данные пользователя будут удалены навсегда.

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                    <form id="forceDeleteForm" action="{{ route('users.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Удалить навсегда</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

                            <!-- Block User Confirm Modal -->
                            <div class="modal fade" id="confirmBlockModal" tabindex="-1" role="dialog" aria-labelledby="confirmBlockModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmBlockModalLabel">{{ $user->status === 'active' ? 'Заблокировать пользователя?' : 'Разблокировать пользователя?' }}</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Вы уверены, что хотите {{ $user->status === 'active' ? 'заблокировать' : 'разблокировать' }} этого пользователя?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                                            <form method="POST" action="{{ route('users.toggleBlock', $user->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-outline-warning">{{$user->status === 'active' ? 'Заблокировать' : 'Разблокировать'}}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('roleSelect');
            const infoBlock = document.getElementById('info_block');
            const infoLabel = document.getElementById('info_label');
            const trainerSelectBlock = document.getElementById('trainer_select_block');

            function updateInterfaceByRole() {
                const selectedRole = roleSelect.value;
                trainerSelectBlock.style.display = 'none';

                if (selectedRole === 'trainer') {
                    infoLabel.innerText = 'Доп информация (награды, опыт):';
                } else if (selectedRole === 'client') {
                    infoLabel.innerText = 'Информация:';
                    trainerSelectBlock.style.display = 'block';
                } else {
                    infoLabel.innerText = 'Информация:';
                }
            }

            updateInterfaceByRole();
            roleSelect.addEventListener('change', updateInterfaceByRole);
        });
    </script>
</x-app-layout>
