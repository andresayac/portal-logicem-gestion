<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('inicio') }}">
                <img src="{{ asset('images/logoF.png') }}" alt="sidebar-logo" width="75%">
            </a>
            <div class="text-center">
                <h6>
                    Portal Interno
                </h6>
            </div>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('inicio') }}">
                <img src="{{ asset('images/logoFSquare.png') }}" alt="sidebar-logo" width="50%">
            </a>
        </div>
        <ul class="sidebar-menu">

            <li class="menu-header">Menú</li>

            <li class="<?= request()->routeIs('inicio') ? 'active' : '' ?>"><a class="nav-link" href="{{ route('inicio') }}"><i class="fas fa-home"></i> <span>Inicio</span></a></li>

            <li class="dropdown <?= request()->routeIs('provisiones*') ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-stream"></i>
                    <span>Provisiones</span></a>
                <ul class="dropdown-menu">
                    <li class="<?= request()->routeIs('provisiones') ? 'active' : '' ?>"><a class="nav-link" href="{{ route('provisiones') }}"><i class="fas fa-stream mr-1"></i>Provisiones</a></li>
                    <li class="<?= request()->routeIs('provisiones.log*') ? 'active' : '' ?>"><a class="nav-link" href="{{ route('provisiones.log') }}"><i class="fas fa-file mr-1"></i>Log</a></li>
                </ul>
            </li>

            <li class="dropdown <?= request()->routeIs('asistente*') ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-passport"></i>
                    <span>Asistente</span></a>
                <ul class="dropdown-menu">
                    <li class="<?= request()->routeIs('asistente') ? 'active' : '' ?>"><a class="nav-link" href="{{ route('asistente') }}"><i class="fas fa-passport mr-1"></i>Asistente</a></li>
                    <li class="<?= request()->routeIs('asistente.log*') ? 'active' : '' ?>"><a class="nav-link" href="{{ route('asistente.log') }}"><i class="fas fa-file mr-1"></i>Log</a></li>
                </ul>
            </li>

        </ul>
    </aside>
</div>