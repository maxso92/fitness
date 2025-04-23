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
    @endphp

    @if(session('access_allowed'))
        <div class="alert alert-success mx-3 mt-3">
            <i class="fas fa-check-circle me-2"></i> Клиенту разрешен доступ в зал
        </div>
    @endif

    <div class="pt-3 px-3 px-lg-6 overflow-hidden">
        <div class="row">
            <!-- Левая колонка - информация о пользователе -->
            <div class="col-lg-4">
                <div class="card mb-3 mb-lg-5">
                    <div class="card-body">
                        <!-- Аватар и ФИО -->
                        <div class="text-center mb-4">
                            <div class="avatar xxl rounded-4 p-1 bg-body overflow-hidden mx-auto mb-3">
                                <img src="{{ $user->avatar_url }}" alt="Аватар" class="img-fluid rounded-4">
                            </div>
                            <h3 class="profile-title mb-1" style="font-size: 28px !important;">
                                {{ $user->surname }} {{ $user->name }} {{ $user->patronymic }}
                            </h3>
                            <p class="text-muted mb-3">
                                {{ $rolesMap[$user->role] ?? $user->role }}
                                @if($user->coach_id)
                                    <span class="badge bg-info ms-2">С тренером</span>
                                @endif
                            </p>
                            <a href="{{ route('users.view', $user->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt me-1"></i> Профиль
                            </a>
                        </div>

                        <!-- Основная информация -->
                        <div class="mb-3">
                            <div class="mb-1 small text-muted">
                                <span class="align-middle material-symbols-rounded me-2 fs-4">mail</span>Email
                            </div>
                            <p class="mb-0">{{ $user->email }}</p>
                        </div>

                        @if($user->birthday)
                        @php
                            $birthday = \Carbon\Carbon::parse($user->birthday);
                            $age = $birthday->age;
                        @endphp
                        <div class="mb-3">
                            <div class="mb-2 small text-muted">
                                <span class="align-middle material-symbols-rounded me-2 fs-4">cake</span>Дата рождения:
                            </div>
                            <p class="mb-0">
                                {{ $birthday->format('d.m.Y') }} ({{ $age }} {{ $age % 100 >= 11 && $age % 100 <= 14 ? 'лет' : match($age % 10) {1 => 'год', 2,3,4 => 'года', default => 'лет'} }})
                            </p>
                        </div>
                        @endif

                        @if(!empty($user->information))
                            <div class="mb-3">
                                <div class="mb-2 small text-muted">
                                    <span class="align-middle material-symbols-rounded me-2 fs-4">info</span>Информация:
                                </div>
                                <p class="mb-0">{{ $user->information }}</p>
                            </div>
                        @endif

                        @if($user->gym_id)
                            <div class="mb-3">
                                <div class="mb-2 small text-muted">
                                    <span class="align-middle material-symbols-rounded me-2 fs-4">fitness_center</span>Зал:
                                </div>
                                <p class="mb-0">
                                    {{ $user->gym_id === 0 ? 'Все залы' : ($user->gym->name ?? 'Не указан') }}
                                </p>
                            </div>
                        @endif

                        @if($user->coach_id)
                            <div class="mb-3">
                                <div class="mb-2 small text-muted">
                                    <span class="align-middle material-symbols-rounded me-2 fs-4">sports</span>Тренер:
                                </div>
                                <p class="mb-0">
                                    <a href="{{ route('users.view', $user->coach->id) }}" target="_blank" class="text-primary">
                                        {{ $user->coach->surname }} {{ $user->coach->name }}
                                    </a>
                                </p>
                            </div>
                        @endif

                        <div class="mt-4 pt-3 border-top">
                            <div class="mb-2 small text-muted">
                                <i class="fas fa-calendar-alt me-2"></i>Регистрация:
                            </div>
                            <p class="mb-0">
                                {{ $createdAt->diffForHumans() }} ({{ $createdAt->format('d.m.Y') }})
                            </p>
                        </div>


                    </div>
                </div>
            </div>

            <!-- Правая колонка - абонементы -->
            <div class="col-lg-8">
                <div class="card mb-3 mb-lg-5">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fas fa-id-card me-2"></i>Абонементы
                        </h4>

                        @if($user->activeSubscriptions->isEmpty())

                                <i class="fas fa-info-circle me-2"></i>Нет активных абонементов

                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Название</th>
                                        <th>Тип</th>
                                        <th>Срок действия</th>
                                        <th>Осталось</th>
                                        <th>Тренер</th>
                                        <th>Статус</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($user->activeSubscriptions as $subscription)
                                        <tr>
                                            <td>{{ $subscription->name }}</td>
                                            <td>{{ $subscription->type === 'time' ? 'По времени' : 'По посещениям' }}</td>
                                            <td>
                                                @if($subscription->pivot->end_date)
                                                    до {{ \Carbon\Carbon::parse($subscription->pivot->end_date)->format('d.m.Y') }}
                                                @else
                                                    Без ограничений
                                                @endif
                                            </td>
                                            <td>
                                                @if($subscription->pivot->remaining_visits)
                                                    {{ $subscription->pivot->remaining_visits }} посещ.
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($subscription->pivot->trainer_id)
                                                    <a href="{{ route('users.view', $subscription->trainer->id) }}" target="_blank" class="text-primary">
                                                        {{ $subscription->trainer->surname }} {{ $subscription->trainer->name }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Активен</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Кнопка разрешения доступа -->
                            @if(!$user->isDeleted && $user->status === 'active')
                                <form method="POST" action="{{ route('visits.allow', $user) }}" class="mt-4">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary">
                                        Разрешить вход в зал
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-danger mt-4">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <strong>Нельзя разрешить вход:</strong>
                                    <ul class="mt-2 mb-0 ps-4">
                                        @if($user->isDeleted)
                                            <li>Пользователь удален</li>
                                        @endif
                                        @if($user->status !== 'active')
                                            <li>Пользователь неактивен</li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
