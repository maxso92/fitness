<x-app-layout>

    @php
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
        abort(403, 'Доступ запрещен');
    }

        $createdAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at);
        $rolesMap = [
            'admin' => 'Администратор',
            'manager' => 'Менеджер',
            'trainer' => 'Тренер',
            'client' => 'Клиент',
        ];
        // Генерация QR-кода только если есть UUID
        $showQrSection = !empty($user->uuid);
        if ($showQrSection) {
            $qrCode = QrCode::size(150)->generate($user->uuid);
        }

    @endphp

    @include('livewire.includes.crud.card-view-header', ['title' => 'Пользователи' , 'actions' => 'Просмотр', 'route' => 'partners'])



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

                        <!-- Роль -->
                        <p class="text-muted mb-0">
                            {{ $rolesMap[$user->role] ?? $user->role }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!--Profile content-->
        <div class="pt-md-3">
            <!--Content-->
            <div class="row">
                <div class="col-lg-4">
                    <!--Card-->
                    <div class="card mb-3 mb-lg-5">
                        <!--Card body-->
                        <div class="card-body">
                            <!-- Блок с QR-кодом -->
                            @if($showQrSection)
                                <div class="text-center mb-4 border-bottom pb-3">
                                    <h5 class="mb-2">QR-код доступа</h5>
                                    <div class="d-inline-block p-2 border rounded">
                                        {!! $qrCode !!}
                                    </div>
                                    <p class="small text-muted mt-2">UUID: {{ $user->uuid }}</p>
                                    <a href="{{ route('users.download-qr', $user->uuid) }}" class="btn btn-sm btn-outline-primary mt-2">
                                        Скачать QR-код
                                    </a>
                                </div>
                            @endif

                            @if ($user->birthday)
                                @php
                                    $birthday = \Carbon\Carbon::parse($user->birthday);
                                    $age = $birthday->age;
                                    if (!function_exists('getAgeEnding')) {
                                        function getAgeEnding($age) {
                                            $n = $age % 100;
                                            if ($n >= 11 && $n <= 14) return 'лет';
                                            switch ($n % 10) {
                                                case 1: return 'год';
                                                case 2:
                                                case 3:
                                                case 4: return 'года';
                                                default: return 'лет';
                                            }
                                        }
                                    }
                                @endphp

                                <div class="mb-3">
                                    <div class="mb-2 small text-muted">
                                        <span class="align-middle material-symbols-rounded me-2 fs-4">cake</span>Дата рождения:
                                    </div>
                                    <p class="mb-0">
                                        {{ $birthday->format('d.m.Y') }}
                                        ({{ $age }} {{ getAgeEnding($age) }})
                                    </p>
                                </div>
                            @endif

                            @if(!empty($user->information))
                                <div class="mb-3">
                                    <div class="mb-2 small text-muted">
                                        <span class="align-middle material-symbols-rounded me-2 fs-4">info</span>Информация:
                                    </div>
                                    <p class="mb-0">{{$user->information}} </p>
                                </div>
                            @endif

                            @if ($user->gym_id)
                                <div class="mt-3">
                                    <div class="mb-2 small text-muted">
                                        <span class="align-middle material-symbols-rounded me-2 fs-4">fitness_center</span>Зал:
                                    </div>
                                    <p class="mb-0">
                                        {{ $user->gym_id === 0 ? 'Все залы' : ($user->gym->name ?? 'Не указан') }}
                                    </p>
                                </div>
                            @endif

                            <!-- Trainer Info (если клиент) -->
                            @if($user->role === 'client' && !empty($user->trainer))
                                <div class="mt-3">
                                    <div class="mb-2 small text-muted">
                                        <span class="align-middle material-symbols-rounded me-2 fs-4">sports</span>Тренер:
                                    </div>
                                    <p class="mb-0">
                                        <a href="{{ route('users.view', $user->trainer->id) }}" data-tippy-content="Перейти в профиль">
                                            {{ $user->trainer->surname }} {{ $user->trainer->name }}
                                        </a>
                                    </p>
                                </div>
                            @endif

                            <div class="mt-3">
                                <div class="mb-2 small text-muted">
                                    <span class="align-middle material-symbols-rounded me-2 fs-4">account_circle</span>Регистрация:
                                </div>
                                <p class="mb-0">
                                    {{ $createdAt->diffInDays(\Carbon\Carbon::now()) }} дн. назад (с {{ $createdAt->format('d/m/Y') }})
                                </p>
                            </div>

                            <div class="mt-3">
                                <div class="mb-2 small text-muted">
                                    <span class="align-middle material-symbols-rounded me-2 fs-4">account_circle</span>Последний вход в ЛК
                                </div>
                                <p class="mb-0">
                                    @if ($user->last_seen_at && \Carbon\Carbon::parse($user->last_seen_at)->isAfter(now()->subMinutes(5)))
                                        <img src="{{asset('assets/icons/Ellipse 12.svg')}}" style="width: 5px; display: inline;" />
                                        Онлайн
                                    @else
                                        <img src="{{asset('assets/icons/Ellipse 13.svg')}}" style="width: 5px; display: inline;" />
                                        {{ $user->last_seen_at ? \Carbon\Carbon::parse($user->last_seen_at)->diffForHumans() : 'Никогда не был онлайн' }}
                                    @endif
                                </p>
                            </div>

                            <div class="mt-3">
                                <div class="mb-1 small text-muted">
                                    <span class="align-middle material-symbols-rounded me-2 fs-4">mail</span>Email
                                </div>
                                <p class="mb-0"> {{$user->email}} </p>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="mt-3 btn btn-outline-primary d-block d-md-inline-block lift">
                                    Редактировать
                                </a>

                                @if($user->role === 'client')
                                    <button class="btn btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#addSubscriptionModal">
                                        Добавить абонемент
                                    </button>
                                @endif


                                @if($user->role === 'trainer')
                                    <button class="btn btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#createTrainingModal">
                                        Добавить тренировку
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!--Card-->
                </div>

                <div class="col-lg-8">
                    <!--Tabs-->
                    <div class="card mb-3 mb-lg-5">
                        <div class="card-body">
                            <div class=" ">
                                <ul class="nav nav-tabs">
                                    @if($user->role === 'client')
                                        <li class="nav-item" style="margin-right: 10px;">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#clientSchedule">Расписание </a>
                                        </li>

                                        <li class="nav-item" style="margin-right: 10px;">
                                            <a class="nav-link" data-bs-toggle="tab" href="#clientTickets">Абонементы</a>
                                        </li>

                                    @endif
                                    @if($user->role === 'trainer')
                                        <li class="nav-item" style="margin-right: 10px;">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#trainerSchedule">Расписание </a>
                                        </li>
                                        <li class="nav-item" style="margin-right: 10px;">
                                            <a class="nav-link" data-bs-toggle="tab" href="#trainerClients">Клиенты </a>
                                        </li>
                                    @endif

                                        <li class="nav-item" style="margin-right: 10px;">
                                            <a class="nav-link" data-bs-toggle="tab" href="#visitHistory">История посещений </a>
                                        </li>

                                    <li class="nav-item" style="margin-right: 10px;">
                                        <a class="nav-link" data-bs-toggle="tab" href="#authHistory">История авторизаций </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    @if($user->role === 'trainer')
                                        <div class="tab-pane fade show active" id="trainerSchedule">
                                            @livewire('trainer-schedule', ['trainerId' => $user->id])
                                        </div>
                                        <div class="tab-pane fade" id="trainerClients">
                                            @livewire('trainer-clients-component', ['trainerId' => $user->id])
                                        </div>
                                    @endif
                                    @if($user->role === 'client')
                                        <div class="tab-pane fade show active" id="clientSchedule">
                                            @livewire('client-schedule', ['clientId' => $user->id])
                                        </div>

                                        <div class="tab-pane fade" id="clientTickets">
                                                @livewire('client-subscriptions', ['clientId' => $user->id])
                                       </div>

                                    @endif

                                        <div class="tab-pane fade" id="visitHistory">
                                            <div>
                                            @livewire('visit-history-component', ['userId' => $user->id])
                                            </div>
                                        </div>

                                    <div class="tab-pane fade" id="authHistory">
                                        <div>
                                            @livewire('auth-history-component', ['userId' => $user->id])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/.Tabs-->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Добавление тренировки -->
    <div class="modal fade" id="createTrainingModal" tabindex="-1" aria-labelledby="createTrainingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('trainings.store') }}">
                @csrf
                <input type="hidden" name="trainer_id" value="{{ $user->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить тренировку</h5>
                        <button type="button" class="btn-outline-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="clients">Клиенты</label>
                            @livewire('client-search')
                            <small class="text-muted">Удерживайте Ctrl (или Cmd) для выбора нескольких.</small>
                        </div>
                        <div class="mb-3">
                            <label for="training_at">Дата и время</label>
                            <input type="datetime-local" name="training_at" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="info">Информация</label>
                            <textarea name="info" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary">Сохранить</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="addSubscriptionModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавить абонемент</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @livewire('client-subscriptions-form', ['clientId' => $user->id], key('client-subscriptions-form-'.$user->id))
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('subscriptionAdded', () => {
                $('#addSubscriptionModal').modal('hide');
            });
        });
    </script>

    <!-- Скрипт для обработки закрытия модалки -->
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('close-modal', () => {
                $('#addSubscriptionModal').modal('hide');
            });
        });
    </script>


</x-app-layout>
