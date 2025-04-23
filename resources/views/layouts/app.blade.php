<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @yield('title')

        <link rel="icon" type="image/png"   href="{{ asset('assets/images/favicon.ico') }}">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" integrity="sha512-ARJR74swou2y0Q2V9k0GbzQ/5vJ2RBSoCWokg4zkfM29Fb3vZEQyv0iWBMW/yvKgyHSR/7D64pFMmU8nYmbRkg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!--Bootstrap icons-->
        <link href="{{ asset('template_admin/assets/fonts/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">

        <!--Google web fonts-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital@0;1&amp;family=Inter:wght@100..900&amp;display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />

        <!--Simplebar css-->
        <link rel="stylesheet" href="{{ asset('template_admin/assets/vendor/css/simplebar.min.css') }}">

        <!--Page style-->
        <link rel="stylesheet" href="{{ asset('template_admin/assets/vendor/css/dragula.min.css') }}">

        <!--Main style-->
        <link rel="stylesheet" href="{{ asset('template_admin/assets/vendor/css/choices.min.css') }}">

        <!--Main style-->
        <link rel="stylesheet" href="{{ asset('template_admin/assets/css/style.min.css') }}">

        <link rel="stylesheet" href="{{ asset('template_assets/css/flash.css') }}">



        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])





         <!-- Styles -->
        @livewireStyles
    </head>

    <body>
    <!--App Start-->
    <div class="d-flex flex-column flex-root">
        <!--Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--///////////Page sidebar begin///////////////-->
            <aside class="page-sidebar">
                <div class="h-100 flex-column d-flex justify-content-start">
                    <!--Aside-logo-->
                    <div class="aside-logo d-flex align-items-center flex-shrink-0 justify-content-start px-3 position-relative">
                        <a href="{{route('dashboard')}}" class="d-block">
                            <div class="d-flex align-items-center flex-no-wrap text-truncate">
                                <!--Logo-icon-->
                                <span class="sidebar-icon d-flex align-items-center justify-content-center fs-4 lh-1 text-white rounded-3 bg-gradient-primary fw-bolder"> <img src="{{asset('assets/images/logo.png')}}" width="55%;"> </span>
                                <span class="sidebar-text">
                            <!--Sidebar-text-->
                            <span class="sidebar-text text-truncate fs-3 fw-bold">
                              Fitness
                            </span>
                          </span>
                            </div>
                        </a>
                    </div>
                    <!--Sidebar-Menu-->
                    <div class="aside-menu my-auto" data-simplebar>

                        @livewire('navigation-menu')

                        <!--aside-info-box-->

                    </div>
                </div>
            </aside>
            <!--///////////Page Sidebar End///////////////-->

            <!--///Sidebar close button for 991px or below devices///-->
            <div
                class="sidebar-close d-lg-none">
                <a href="#"></a>
            </div>
            <!--///.Sidebar close end///-->


            <!--///////////Page content wrapper///////////////-->
            <div class="page-content d-flex flex-column flex-row-fluid">

                <!--//page-header//-->
                <header class="navbar transition-base border-bottom mb-3 px-3 px-lg-6 px-3 px-lg-6 align-items-center page-header navbar-expand navbar-light">
                    <a href="{{route('dashboard')}}" class="navbar-brand d-block d-lg-none">
                        <div class="d-flex align-items-center flex-no-wrap text-truncate">
                            <!--Sidebar-icon-->
                            <span class="sidebar-icon bg-gradient-primary rounded-3 size-40 fw-bolder text-white">
                             F
                          </span>
                        </div>
                    </a>
                    <ul class="navbar-nav d-flex align-items-center h-100">
                        <li class="nav-item d-none d-lg-flex flex-column h-100 me-1 align-items-center justify-content-center" data-tippy-placement="bottom-start" data-tippy-content="Свернуть сайдбар">
                            <a href="javascript:void(0)"
                               class="sidebar-trigger nav-link size-40 d-flex align-items-center justify-content-center p-0">
                            <span class="material-symbols-rounded fs-4">
                              menu_open
                              </span>
                            </a>
                        </li>



                     </ul>
                    <ul class="navbar-nav ms-auto d-flex align-items-center h-100">

                        <!--:Dark Mode:-->
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link p-0 size-40 dropdown-toggle d-flex align-items-center justify-content-center" id="bs-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static">
                            <span class="theme-icon-active d-flex align-items-center">
                              <span class="material-symbols-rounded align-middle fs-4"></span>
                            </span>
                            </a>
                            <!--:Dark Mode Options:-->
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bs-theme" style="--bs-dropdown-min-width: 9rem;">
                                <li class="mb-1">
                                    <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light">
                                  <span class="theme-icon d-flex align-items-center">
                                    <span class="material-symbols-rounded fs-4 align-middle me-2">
                                      lightbulb
                                      </span>
                                    </span>
                                        Светлая
                                    </button>
                                </li>
                                <li class="mb-1">
                                    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark">
                                  <span class="theme-icon d-flex align-items-center">
                                    <span class="material-symbols-rounded fs-4 align-middle me-2">
                                      dark_mode
                                      </span>
                                    </span>
                                        Темная
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto">
                                  <span class="theme-icon d-flex align-items-center">
                                    <span class="material-symbols-rounded fs-4 align-middle me-2">
                                      invert_colors
                                      </span>
                                    </span>
                                        Авто
                                    </button>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown d-flex align-items-center justify-content-center flex-column h-100">
                            <a href="#offcanvas_user"
                               class="nav-link height-40 px-2 d-flex align-items-center justify-content-center"
                               aria-expanded="false" data-bs-toggle="offcanvas">
                                <div class="d-flex align-items-center">

                                    <!--Avatar with status-->
                                    <!--Avatar with status-->
                                    <div class="avatar-status status-online me-sm-2 avatar xs">
                                        <img src="{{ Auth::user()->avatar_url }}" class="rounded-circle img-fluid" alt="">
                                    </div>
                                    <span class="d-none d-md-inline-block">{{ Auth::user()->email }}</span>
                                </div>
                            </a>
                        </li>
                        <li
                            class="nav-item dropdown ms-1 d-flex d-lg-none align-items-center justify-content-center flex-column h-100">
                            <a href="javascript:void(0)"
                               class="nav-link sidebar-trigger-lg-down size-40 p-0 d-flex align-items-center justify-content-center">
                                <span class="material-symbols-rounded fs-3 align-middle">menu</span>
                            </a>
                        </li>
                    </ul>
                </header>
                <!--Main Header End-->

                <!--:User offcanvas menu:-->

                <div class="offcanvas offcanvas-end border-0" style="--bs-offcanvas-width: 290px;" id="offcanvas_user">
                    <div class="offcanvas-body p-0">
                        <!--User meta-->
                        <div class="position-relative overflow-hidden offcanvas-header px-3 pt-6 pb-10 bg-body-secondary">
                            <!--Divider-->
                            <svg style="transform: rotate(-180deg);color:var(--bs-offcanvas-bg)" preserveAspectRatio="none"
                                 class="position-absolute start-0 bottom-0 w-100" fill="currentColor" height="24" viewBox="0 0 1200 120"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M0 0v46.29c47.79 22.2 103.59 32.17 158 28 70.36-5.37 136.33-33.31 206.8-37.5 73.84-4.36 147.54 16.88 218.2 35.26 69.27 18 138.3 24.88 209.4 13.08 36.15-6 69.85-17.84 104.45-29.34C989.49 25 1113-14.29 1200 52.47V0z"
                                    opacity=".25" />
                                <path
                                    d="M0 0v15.81c13 21.11 27.64 41.05 47.69 56.24C99.41 111.27 165 111 224.58 91.58c31.15-10.15 60.09-26.07 89.67-39.8 40.92-19 84.73-46 130.83-49.67 36.26-2.85 70.9 9.42 98.6 31.56 31.77 25.39 62.32 62 103.63 73 40.44 10.79 81.35-6.69 119.13-24.28s75.16-39 116.92-43.05c59.73-5.85 113.28 22.88 168.9 38.84 30.2 8.66 59 6.17 87.09-7.5 22.43-10.89 48-26.93 60.65-49.24V0z"
                                    opacity=".5" />
                                <path
                                    d="M0 0v5.63C149.93 59 314.09 71.32 475.83 42.57c43-7.64 84.23-20.12 127.61-26.46 59-8.63 112.48 12.24 165.56 35.4C827.93 77.22 886 95.24 951.2 90c86.53-7 172.46-45.71 248.8-84.81V0z" />
                            </svg>
                            <div class="position-relative flex-grow-1">
                                <div>
                                    @if (Auth::user()->avatar_url)
                                    <div class="flex-shrink-0 me-3">

                                        <img src="{{ Auth::user()->avatar_url }}" class="rounded-circle shadow width-60 d-block mx-auto img-fluid" alt="">
                                    </div>
                                    @endif

                                    <div class="text-center pt-4">
                                        <h5 class="mb-1">{{ Auth::user()->name }} {{ Auth::user()->surname }}  </h5>
                                        <p class="text-body-tertiary mb-0 lh-1">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm px-2 btn-white position-absolute end-0 top-0 me-2 mt-2" data-bs-dismiss="offcanvas">
                                <span class="material-symbols-rounded fs-5 align-middle">close</span>
                            </button>
                        </div>

                    </div>
                    <div class="offcanvas-footer border-top rounded-0 list-group p-3">
                        <form action="{{ route('logout') }}" method="POST">
                            <button type="submit" class="list-group-item-action rounded px-2 py-1 d-flex align-items-center {{ Request::is('logout') ? 'active' : '' }}">
            <span class="material-symbols-rounded align-middle me-2 size-30 fs-4 d-flex align-items-center justify-content-center text-primary">
                logout
            </span>
                                <span class="flex-grow-1">Выход</span>
                            </button>
                            {{ csrf_field() }} </form>
                    </div>


                </div>



        <x-banner />



                {{ $slot }}




                <!--//Page-footer//-->
                <footer class="p-2 px-3 px-lg-6 pt-5">
                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                          <span class="d-block lh-sm small text-body-secondary text-end">&copy;
                            <script>
                              document.write(new Date().getFullYear())
                            </script>. Fitness System
                          </span>
                    </div>
                    </div>
                    </div>
                </footer>
                <!--/.Page Footer End-->

            <!--///////////Page content wrapper End///////////////-->
        </div>
    </div>

    <!--////////////Theme Core scripts Start/////////////////-->

    <!--////////////Theme Core scripts End/////////////////-->
        <!--////////////Theme Core scripts End/////////////////-->

        @stack('modals')
        @stack('scripts')

        @livewireScripts

         <script src="{{ asset('template_admin/assets/js/theme.bundle.js')}}"></script>



        <script>
            var intervalId;

            function updateUserActivity() {
                $.ajax({
                    url: '/user/activity',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Если используется CSRF защита
                    },
                    success: function(response) {
                     //   console.log('Активность пользователя обновлена');
                    },
                    error: function(xhr, status, error) {
                   //     console.error('Произошла ошибка при отправке запроса');
                    }
                });
            }

            // Функция для обновления активности пользователя при загрузке страницы
            $(document).ready(function() {
                updateUserActivity();
            });

            // Функция для обновления активности пользователя при клике на ссылки
            $('a').on('click', function() {
                updateUserActivity();
            });

            // Функция для обновления активности пользователя при перед выгрузкой страницы (переходе на другую страницу)
            $(window).on('beforeunload', function() {
                updateUserActivity();
            });

        </script>




    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 4000);  // 4000 миллисекунд = 4 секунды
    </script>



        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var toastLiveExample = document.getElementById('liveToast');
                if (toastLiveExample) {
                    var toast = new bootstrap.Toast(toastLiveExample);
                    toast.show();
                }
            });
        </script>




    </body>
</html>
