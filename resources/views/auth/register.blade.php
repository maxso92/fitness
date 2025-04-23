<x-login-layout>

    @section('title')
        <title>Регистрация на сайте.</title>
    @endsection
    @section('description')
        <meta property="og:description" content="Регистрация в системе">
        <meta name="description" content="Регистрация в системе">
    @endsection

        <div class="content p-1 d-flex flex-column-fluid position-relative">
            <div class="container py-4">
                <div class="row h-100 align-items-center justify-content-center">
                    <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">



                        <div class="card card-body p-4">
                            <h4 class="text-center">Регистрация в системе</h4>


                            <hr class="mt-4">

                            <p class="mb-4 text-center text-body-secondary">
                                Для начала, пожалуйста, зарегистрируйтесь, указав детали.
                            </p>

                            <form method="POST" action="{{ route('register') }}" class="z-1 position-relative needs-validation" novalidate>
                                @csrf

                                @if(App\Models\User::count() === 0)


                                <div class="row g-3">

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="surname" name="surname" required>
                                            <label for="surname">Фамилия *</label>
                                        </div>



                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="name" name="name" required>
                                            <label for="name">Имя *</label>
                                        </div>



                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="patronymic" name="patronymic">
                                            <label for="patronymic">Отчество</label>
                                        </div>


                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <label for="email">Email *</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <label for="password">Пароль *</label>
                                </div>

                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    <label for="password_confirmation">Подтверждение пароля *</label>
                                </div>

                                <div class="d-grid">
                                    <button class="w-100 btn btn-lg btn-primary" type="submit">
                                        Зарегистрироваться
                                    </button>
                                </div>
                            </form>
                            @else
                                <div class="alert alert-warning text-center">
                                    Регистрация отключена. Пользователь уже существует.
                                </div>
                            @endif

                         </div>
                    </div>
                </div>
            </div>
        </div>

</x-login-layout>
