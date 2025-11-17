<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 my-2" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand px-4 py-3 m-0" href="{{ route('welcome') }}">
            <img src="{{ asset('admin2/assets/img/pol.png') }}" class="navbar-brand-img" width="40" height="50"
                alt="main_logo">
            {{-- Tambahkan text-dark agar konsisten --}}
            <span class="ms-1 text-sm text-dark font-weight-bold">Crime Zone</span> 
        </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">           
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('dashboard') }}">
                    <i class="material-symbols-rounded opacity-5">dashboard</i>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('data.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" 
                    href="{{ route('data.index') }}">
                    <i class="material-symbols-rounded opacity-5">table_view</i>
                    <span class="nav-link-text ms-1">Data Kriminal</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cluster*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('cluster') }}">
                    <i class="material-symbols-rounded opacity-5">chart_data</i>
                    <span class="nav-link-text ms-1">Cluster</span>
                </a>
            </li>
            <li class="nav-item">
                {{-- Memperbaiki nama pemanggilan route --}}
                <a class="nav-link {{ request()->routeIs('prioritas*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('prioritas') }}"> 
                    <i class="material-symbols-rounded opacity-5">star_rate</i>
                    <span class="nav-link-text ms-1">Prioritas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('peta*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" 
                    href="{{ route('peta') }}">
                    <i class="material-symbols-rounded opacity-5">map</i>
                    <span class="nav-link-text ms-1">Peta</span>
                </a>
            </li>

        </ul>
    </div>
</aside>