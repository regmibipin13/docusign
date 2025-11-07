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

    <style>
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(180deg, #ffffff 0%, #fafbfc 100%);
            padding: 2rem 0;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--tblr-border-color);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .auth-header {
            text-align: center;
            padding: 2.5rem 2rem 1.5rem;
            border-bottom: 1px solid var(--tblr-border-color);
        }

        .auth-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            text-decoration: none;
        }

        .auth-logo img {
            height: 45px;
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .auth-subtitle {
            color: var(--tblr-muted);
            font-size: 0.9375rem;
            margin: 0;
        }

        .auth-body {
            padding: 2rem;
        }

        .auth-footer {
            padding: 1.5rem 2rem;
            background: #fafbfc;
            border-top: 1px solid var(--tblr-border-color);
            text-align: center;
            font-size: 0.9375rem;
        }

        .auth-footer a {
            color: var(--tblr-primary);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .form-label {
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 0.5rem;
            font-size: 0.9375rem;
        }

        .form-control {
            padding: 0.625rem 0.875rem;
            border-radius: 6px;
            border: 1px solid var(--tblr-border-color);
            transition: all 0.2s ease;
            font-size: 0.9375rem;
        }

        .form-control:focus {
            border-color: var(--tblr-primary);
            box-shadow: 0 0 0 3px rgba(15, 124, 193, 0.1);
        }

        .auth-btn {
            width: 100%;
            padding: 0.75rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.9375rem;
            transition: all 0.2s ease;
        }

        .auth-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 124, 193, 0.25);
        }

        .auth-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--tblr-border-color);
        }

        .auth-divider span {
            padding: 0 1rem;
            color: var(--tblr-muted);
            font-size: 0.875rem;
        }

        .auth-alert {
            border-radius: 6px;
            padding: 0.875rem 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
            font-size: 0.9375rem;
        }

        .auth-alert-success {
            background: rgba(47, 179, 68, 0.1);
            border-color: rgba(47, 179, 68, 0.2);
            color: #1e7e34;
        }

        .auth-alert-danger {
            background: rgba(235, 34, 39, 0.1);
            border-color: rgba(235, 34, 39, 0.2);
            color: #d11e22;
        }

        .form-text {
            font-size: 0.875rem;
            color: var(--tblr-muted);
            margin-top: 0.375rem;
        }

        .back-to-home {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            color: var(--tblr-muted);
            text-decoration: none;
            font-size: 0.9375rem;
            margin-bottom: 1.5rem;
            transition: color 0.2s ease;
        }

        .back-to-home:hover {
            color: var(--tblr-primary);
        }

        .back-to-home i {
            font-size: 1.25rem;
        }
    </style>

    @yield('styles')
</head>

<body>
    <div id="app">
        <div class="auth-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <a href="{{ route('frontend.home') }}" class="back-to-home">
                            <i class='bx bx-arrow-back'></i>
                            <span>Back to home</span>
                        </a>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @yield('scripts')
</body>

</html>
