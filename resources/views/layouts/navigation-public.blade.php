<!-- ======= Header ======= -->
<header id="header" class="fixed-top header-scrolled">
    <div class="container">

        <nav class="navbar navbar-expand-lg navbar-dark px-0">

            {{-- Logo --}}
            <a href="/" class="navbar-brand logo">
                <img src="{{ asset('img/logos/cultura_blanco-02.webp') }}" alt="" class="img-fluid" id="logo-img">
            </a>

            {{-- Hamburguesa --}}
            <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMain"
                aria-controls="navbarMain"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Links --}}
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item px-lg-2">
                        <a class="nav-link animation-sub" href="{{ url('/') }}">Inicio</a>
                    </li>
                    <li class="nav-item px-lg-2">
                        <a class="nav-link animation-sub" href="{{ url('/artistas') }}">Artistas</a>
                    </li>
                    @auth
                    <li class="nav-item px-lg-2">
                        <a class="nav-link animation-sub" href="{{ route('artista.mis-perfiles') }}">Mis perfiles</a>
                    </li>
                    @endauth

                    @auth
                        {{-- Usuario logueado --}}
                        <li class="nav-item dropdown px-lg-2">
                            <a class="nav-link nav-link-user dropdown-toggle" href="#"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark custom-dropdown-width">
                                @role('admin')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Panel de administración</a>
                                    </li>
                                @endrole
                                @role('artista')
                                    @if (auth()->user()->inscripcion != null)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('artista-inscripcion.edit') }}">Tu inscripción</a>
                                        </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">Tus datos</a>
                                    </li>
                                @endrole
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-start">Cerrar sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        {{-- Invitado --}}
                        <li class="nav-item ps-lg-2">
                            <a class="btn btn-red rounded-pill px-3" href="{{ route('register') }}">Registrate</a>
                        </li>
                    @endauth

                </ul>
            </div>

        </nav>
    </div>
</header>
