<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">صفحات</div>
                @if(auth()->user()->type == 'admin')
                    <a class="nav-link" href="{{ route('admins.dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        داشبورد
                    </a>
                    <a class="nav-link" href="{{ route('teachers.settings') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                        تنظیمات
                    </a>
                @elseif(auth()->user()->type == 'teacher')
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        داشبورد
                    </a>
                @endif
                <a class="nav-link" onclick="event.preventDefault(); document.getElementById('logout_form').submit();" href="{{ route('logout') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-sign-out"></i></div>
                    خروج
                </a>
                <form action="{{ route('logout') }}" id="logout_form" method="POST">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </nav>
</div>
