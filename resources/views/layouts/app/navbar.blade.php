<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>

    <ul class="navbar-nav navbar-right">
        @impersonating($guard = null)
            <li>
                <div class="">
                    <a href="{{ route('impersonate.leave') }}" class="btn btn-light">{{ __('Regresar como Admin') }}</a>
                </div>
            </li>
        @endImpersonating
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('theme/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->username }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <!-- <div class="dropdown-divider"></div> -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                        class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </form>
            </div>
        </li>
    </ul>
</nav>
