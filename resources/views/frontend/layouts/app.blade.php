<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'QuikSign - Digital Document Signing Made Easy')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/frontend/app.scss', 'resources/js/frontend/app.js'])

    @yield('styles')
</head>

<body>
    <div id="app">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="{{ route('frontend.home') }}">
                    <img src="{{ asset('logo.png') }}" alt="QuikSign by Harbour College" style="height: 40px;">
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-lg-center">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('frontend.home') }}#features">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('frontend.home') }}#how-it-works">How It Works</a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('customer.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link nav-link p-0">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Sign In</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-primary btn-sm ms-2 px-3" href="{{ route('register') }}">
                                    Get Started
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        @yield('content')

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <img src="{{ asset('logo.png') }}" alt="QuikSign by Harbour College"
                            style="height: 35px; margin-bottom: 1rem;">
                        <p class="text-muted">
                            Digital document signing made easy. Sign, share, and manage your documents securely online.
                        </p>
                    </div>
                    <div class="col-md-4 mb-4">
                        <h5 class="mb-3">Quick Links</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('frontend.home') }}">Home</a></li>
                            <li class="mb-2"><a href="{{ route('frontend.home') }}#features">Features</a></li>
                            <li class="mb-2"><a href="{{ route('frontend.home') }}#how-it-works">How It Works</a>
                            </li>
                            @auth
                                <li class="mb-2">
                                    <a
                                        href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('customer.dashboard') }}">
                                        Dashboard
                                    </a>
                                </li>
                            @else
                                <li class="mb-2"><a href="{{ route('login') }}">Login</a></li>
                                <li class="mb-2"><a href="{{ route('register') }}">Register</a></li>
                            @endauth
                        </ul>
                    </div>
                    <div class="col-md-4 mb-4">
                        <h5 class="mb-3">Contact</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class='bx bx-envelope'></i> support@quiksign.test
                            </li>
                            <li class="mb-2">
                                <i class='bx bx-phone'></i> +1 (555) 123-4567
                            </li>
                            <li class="mb-2">
                                <i class='bx bx-map'></i> 123 Business St, Suite 100
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p class="text-muted mb-0">
                            &copy; {{ date('Y') }} QuikSign by Harbour College. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @yield('scripts')
</body>

</html>
