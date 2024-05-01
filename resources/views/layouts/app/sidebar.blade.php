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

            <li class="<?= request()->routeIs('inicio') ? 'active' : '' ?>"><a class="nav-link"
                    href="{{ route('inicio') }}"><i class="fas fa-home"></i> <span>Inicio</span></a></li>

            @if (session('is_admin'))
                <li class="<?= request()->routeIs('logs') ? 'active' : '' ?>">
                    <a class="nav-link" href="{{ route('logs') }}"><i class="far fa-file-alt"></i>
                        <span>Logs</span></a>
                </li>
            @else
                <li class="dropdown <?= request()->routeIs('documentos*') ? 'active' : '' ?>">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-stream"></i>
                        <span>Documentos</span></a>
                    <ul class="dropdown-menu">
                        <li class="<?= request()->routeIs('documentos.certificado-retenciones') ? 'active' : '' ?>">
                            <a class="nav-link" href="{{ route('documentos.certificado-retenciones') }}">
                                <i class="fas fa-stream mr-1"></i>Certificado Retenciones
                            </a>
                        </li>
                        <li class="<?= request()->routeIs('documentos.facturas-registradas') ? 'active' : '' ?>">
                            <a class="nav-link" href="{{ route('documentos.facturas-registradas') }}">
                                <i class="fas fa-stream mr-1"></i>Facturas registradas
                            </a>
                        </li>
                        <li class="<?= request()->routeIs('documentos.pagos-efectuados') ? 'active' : '' ?>">
                            <a class="nav-link" href="{{ route('documentos.pagos-efectuados') }}">
                                <i class="fas fa-stream mr-1"></i>Pagos efectuados
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </aside>
</div>
