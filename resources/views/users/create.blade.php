<x-app-layout>
    @php($route = 'partners')

    @include('livewire.includes.crud.card-add-header', ['title' => 'Пользователи', 'actions' => 'добавление', 'route' => $route])

    <div class="content pt-3 px-3 px-lg-6">
        <div class="row">
            <div class="col-12 col-lg-8 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <form id="form" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="uuid" value="{{ Str::uuid() }}">

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
                                        <input type="text" name="surname" value="{{ old('surname') }}" class="form-control" required>
                                        @error('surname')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="name" class="form-label">Имя:</label>
                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                                        @error('name')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="patronymic" class="form-label">Отчество:</label>
                                        <input type="text" name="patronymic" value="{{ old('patronymic') }}" class="form-control">
                                        @error('patronymic')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="role" class="form-label">Роль:</label>
                                        @php($currentRole = auth()->user()->role)

                                        <select name="role" id="roleSelect" class="form-control">
                                            @if($currentRole === 'admin')
                                                <option value="admin" @if(old('role') == 'admin') selected @endif>Администратор</option>
                                                <option value="manager" @if(old('role') == 'manager') selected @endif>Менеджер</option>
                                            @endif

                                            @if(in_array($currentRole, ['admin', 'manager']))
                                                <option value="trainer" @if(old('role') == 'trainer') selected @endif>Тренер</option>
                                                <option value="client" @if(old('role') == 'client') selected @endif>Клиент</option>
                                            @endif
                                        </select>

                                        @error('role')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                                        @error('email')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="birthday" class="form-label">Дата рождения:</label>
                                        <input type="date" name="birthday" value="{{ old('birthday') }}" class="form-control">
                                        @error('birthday')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="password" class="form-label">Пароль:</label>
                                        <input type="password" name="password" class="form-control" required>
                                        @error('password')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="password_confirmation" class="form-label">Подтверждение пароля:</label>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="gym_id" class="form-label">Зал:</label>
                                        <select name="gym_id" class="form-control">
                                            <option value="">-- Не выбрано --</option>
                                            <option value="0" @if(old('gym_id') == 0) selected @endif>Все залы</option>
                                            @foreach(App\Models\Gym::all() as $gym)
                                                <option value="{{ $gym->id }}" @if(old('gym_id') == $gym->id) selected @endif>{{ $gym->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('gym_id')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-xl-4 mb-3">
                                        <label for="status" class="form-label">Статус:</label>
                                        <select name="status" class="form-control">
                                            <option value="active" selected>Активный</option>
                                            <option value="inactive">Неактивный</option>
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
                                                <option value="{{ $trainer->id }}" @if(old('trainer_id') == $trainer->id) selected @endif>
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
                                        <textarea name="information" rows="5" class="form-control">{{ old('information') }}</textarea>
                                        @error('information')
                                        <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Правая колонка с кнопками и информацией о UUID -->
            <div class="col-12 col-lg-4 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button form="form" type="submit" class="btn btn-outline-primary">Сохранить</button>
                            <a href="{{ route('partners') }}" class="btn btn-outline-secondary">Назад</a>
                        </div>

                        <!-- Блок с информацией о UUID -->
                        <div class="mt-4">
                            <h6 class="mb-3">Идентификатор доступа</h6>
                            <div class=" ">
                                <p class="small mb-1"><strong>UUID:</strong></p>
                                <code class="d-block mb-2">{{ Str::uuid() }}</code>
                                <p class="small mb-0">Этот уникальный идентификатор будет использоваться:</p>
                                <ul class="small ps-3 mb-0">
                                    <li>Для генерации QR-кода</li>
                                    <li>Для контроля доступа в зал</li>
                                    <li>Как уникальный ключ пользователя</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('roleSelect');
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
