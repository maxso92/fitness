<x-login-layout>
    @section('title')
        <title> Войти в личный кабинет.</title>
    @endsection
    @section('description')
        <meta property="og:description" content="Вход в личный кабинет">
        <meta name="description" content="Вход в личный кабинет">
    @endsection

    <div class="content p-1 d-flex flex-column-fluid position-relative">
        <div class="container py-4">
            <div class="row h-100 align-items-center justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">

                    <div class="card card-body p-4">
                        <h4 class="text-center">Вход</h4>
                        <p class="mb-4 text-body-secondary text-center">
                            Войдите со своими учетными данными.
                        </p>

                        <x-validation-errors class="mb-4" />

                        @if (session('status'))
                            <div class="mb-4 font-medium text-blue-600">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="mb-4 alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <hr class="mt-4 mb-3">

                        {{-- Login Form --}}
                        <form method="POST" action="{{ route('login') }}" class="position-relative needs-validation" novalidate>
                            @csrf
                            <div class="form-floating mb-3">
                                <x-input   style="border-color: #e4e4e7;"  id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus placeholder="name@example.com" />
                                <label for="email">Email адрес</label>
                                <span class="invalid-feedback">Пожалуйста, введите действительный email адрес</span>
                            </div>
                            <div class="form-floating mb-3">
                                <x-input   style="border-color: #e4e4e7;"  id="password" class="form-control" type="password" name="password" required placeholder="Пароль" />
                                <label for="password">Пароль</label>
                                <span class="invalid-feedback">Введите пароль</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="form-check">
                                    <input class="form-check-input me-1" id="remember" type="checkbox" name="remember">
                                    <label class="form-check-label" for="remember">Запомнить меня</label>
                                </div>

                            </div>
                            <button class="w-100 btn btn-lg btn-primary" type="submit">Войти</button>
                            <hr class="mt-4 mb-3">

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-login-layout>
