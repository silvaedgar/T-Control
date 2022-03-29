<!-- Navbar -->

<nav  class="navbar navbar-expand-lg navbar-absolute" >
    <div class="container-fluid">
        <a class="navbar-brand font-bold" href="#">{{ $titlePage }} </a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul style="list-style: none">
                    <li class="nav-item dropdown">
                    <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">person</i> {{ Auth::user()->name }}
                    <p class="d-lg-none d-md-block">
                        {{ __('Cuenta de usuario') }}
                    </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Perfil de Usuario') }}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Log out') }}</a>
                    </div>
                </li>

            </ul>
            <ul style="list-style: none">
            </ul>
        </div>
    </div>
</nav>
