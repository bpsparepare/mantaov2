<header class="app-header">
    <div class="header-content">
        {{-- LOGO --}}
        <a href="{{ route('dashboard') }}" class="logo-container">
            <img src="/images/Logo-mantao.png" alt="Logo MANTAO" class="logo-image">
            <span class="logo-text">MANTAO</span>
        </a>

        {{-- NAVIGASI MENU --}}
        <nav class="main-nav">
            <ul>
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="https://lookerstudio.google.com/u/0/reporting/f79ebd9d-c4ef-427d-ac0f-a41f638beef2" target="_blank">Monitoring</a></li>

                {{-- KONDISI BARU: Hanya tampilkan menu ini jika role adalah admin --}}
                @if(Session::has('user') && Session::get('user')['role'] === 'admin')
                <li><a href="{{ route('internal.index') }}" class="{{ request()->routeIs('internal.index') ? 'active' : '' }}">Internal</a></li>
                @endif

                <li><a href="https://api.whatsapp.com/send/?phone=6285854404512&text&type=phone_number&app_absent=0" target="_blank">Narahubung</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-button">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>

        {{-- TOMBOL HAMBURGER --}}
        <button class="mobile-nav-toggle" aria-controls="main-nav-ul" aria-expanded="false">
            <span class="sr-only">Buka menu</span>
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
        </button>
    </div>
</header>