<nav class="flex-grow-1 h-100" id="page-navbar">
    <!--Sidebar nav-->
    <ul class="nav flex-column collapse-group collapse d-flex">


        @auth
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                <li class="nav-item">
                    <a href="{{ route('scan.qr.form') }}" class="nav-link d-flex align-items-center text-truncate">
                <span class="sidebar-icon">
                    <span class="material-symbols-rounded">
                        qr_code_scanner
                    </span>
                </span>
                        <span class="sidebar-text">Сканировать QR</span>
                    </a>
                </li>

            <li class="nav-item">
                <a href="{{ route('partners') }}" class="nav-link d-flex align-items-center text-truncate">
                <span class="sidebar-icon">
                    <span class="material-symbols-rounded">
                        account_box
                    </span>
                </span>
                    <span class="sidebar-text">Пользователи</span>
                </a>
            </li>


            <li class="nav-item">
                <a href="{{ route('schedules') }}" class="nav-link d-flex align-items-center text-truncate">
                <span class="sidebar-icon">
                    <span class="material-symbols-rounded">
                        calendar_month
                    </span>
                </span>
                    <span class="sidebar-text">Расписание</span>
                </a>
            </li>



            <li class="nav-item">
                <a href="{{ route('subscriptions') }}" class="nav-link d-flex align-items-center text-truncate">
                <span class="sidebar-icon">
                    <span class="material-symbols-rounded">
                        toolbar
                    </span>
                </span>
                    <span class="sidebar-text">Абонементы</span>
                </a>
            </li>


            <li class="nav-item">
                <a href="{{ route('gyms') }}" class="nav-link d-flex align-items-center text-truncate">
                <span class="sidebar-icon">
                    <span class="material-symbols-rounded">
                        fitness_center
                    </span>
                </span>
                    <span class="sidebar-text">Залы</span>
                </a>
            </li>


            @endif
        @endauth
    </ul>
</nav>
