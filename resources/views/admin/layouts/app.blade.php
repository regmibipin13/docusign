<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name') }}</title>

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite(['resources/css/admin/app.scss', 'resources/js/admin/app.js'])

    @stack('styles')
</head>

<body>
    <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
        <div class="sidebar-brand d-none d-md-flex">
            <svg class="sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
                <use xlink:href="#logo-full"></use>
            </svg>
            <svg class="sidebar-brand-narrow" width="46" height="46" alt="CoreUI Logo">
                <use xlink:href="#logo-narrow"></use>
            </svg>
        </div>
        <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class='bx bxs-dashboard nav-icon'></i> Dashboard
                </a>
            </li>
            <li class="nav-title">Management</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}">
                    <i class='bx bxs-user-account nav-icon'></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}"
                    href="{{ route('admin.documents.index') }}">
                    <i class='bx bxs-file-pdf nav-icon'></i> Documents
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.signatures.*') ? 'active' : '' }}"
                    href="{{ route('admin.signatures.index') }}">
                    <i class='bx bx-pen nav-icon'></i> Signatures
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class='bx bxs-cog nav-icon'></i> Settings
                </a>
            </li>
        </ul>
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>

    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <header class="header header-sticky mb-4">
            <div class="container-fluid">
                <button class="header-toggler px-md-0 me-md-3" type="button"
                    onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                    <i class='bx bx-menu icon icon-lg'></i>
                </button>
                <a class="header-brand d-md-none" href="#">
                    <svg width="118" height="46">
                        <use xlink:href="#logo-full"></use>
                    </svg>
                </a>
                <ul class="header-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class='bx bxs-bell icon icon-lg'></i>
                        </a>
                    </li>
                </ul>
                <ul class="header-nav ms-3">
                    <li class="nav-item dropdown">
                        <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <div class="avatar avatar-md">
                                <img class="avatar-img"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=321fdb&color=fff"
                                    alt="{{ auth()->user()->name }}">
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <div class="dropdown-header bg-light py-2">
                                <div class="fw-semibold">Account</div>
                            </div>
                            <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                <i class='bx bxs-user me-2'></i> Profile
                            </a>
                            <a class="dropdown-item" href="{{ route('admin.profile.password') }}">
                                <i class='bx bxs-lock me-2'></i> Change Password
                            </a>
                            <a class="dropdown-item" href="{{ route('admin.profile.two-factor') }}">
                                <i class='bx bxs-shield me-2'></i> Two-Factor Auth
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class='bx bx-log-out me-2'></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </header>

        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-coreui-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-coreui-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        <footer class="footer">
            <div>
                <a href="#">{{ config('app.name') }}</a>
                <span class="ms-1">&copy; {{ date('Y') }} Admin Panel.</span>
            </div>
            <div class="ms-auto">
                Powered by <a href="#">CoreUI</a>
            </div>
        </footer>
    </div>

    <!-- Logo SVG -->
    <svg style="display: none;">
        <symbol id="logo-full" viewBox="0 0 118 46">
            <title>CoreUI Logo</title>
            <g>
                <g><text x="10" y="30" font-size="24" fill="currentColor" font-weight="bold">Admin</text></g>
            </g>
        </symbol>
        <symbol id="logo-narrow" viewBox="0 0 46 46">
            <title>CoreUI Logo</title>
            <g><text x="13" y="30" font-size="24" fill="currentColor" font-weight="bold">A</text></g>
        </symbol>
    </svg>

    @stack('scripts')
</body>

</html>
