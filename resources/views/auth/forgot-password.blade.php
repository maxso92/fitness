<x-login-layout>

    @section('title')
        <title>Восстановление пароля.</title>
    @endsection
    @section('description')
        <meta property="og:description" content="Восстановление пароля на сайте">
        <meta name="description" content="Восстановление пароля на сайте">
    @endsection



        <div class="content p-1 d-flex flex-column-fluid position-relative">
            <div class="container py-4">
                <div class="row h-100 align-items-center justify-content-center">
                    <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">

                        <a href=" " class="d-flex position-relative mb-4 z-1 align-items-center justify-content-center">

 <span class="sidebar-icon size-60 bg-gradient-primary text-white rounded-3">
                                      <img src="{{asset('template_public_assets/assets/img/rentcrm_icon.png')}}" width="55%;">
                                        </span>
                        </a>

                        <div class="card card-body p-4">
                            <h4 class="text-center">Восстановление пароля</h4>
                            <form method="POST" action="{{ route('password.email') }}" class="z-1 position-relative needs-validation" novalidate>
                                @csrf
                                <x-validation-errors class="mb-4" />
                                @if (session('status'))
                                    <div class="mb-4 font-medium text-blue-600">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <div class="form-floating mb-3">
                                    <x-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus placeholder="E-mail" />
                                    <label for="email">Ваш email</label>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-lg btn-primary">Восстановить</button>
                                    <p class="pt-4 small">Нет учетной записи? <a href="{{ route('register') }}" class="ms-2 fw-semibold link-underline">Зарегистрироваться</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>




</x-login-layout>
