@extends('auth.layouts.auth')

@section('title', 'Sign In - QuikSign')

@section('content')
    <div class="auth-card">
        <div class="auth-header">
            <a href="{{ route('frontend.home') }}" class="auth-logo">
                <img src="{{ asset('logo.png') }}" alt="QuikSign by Harbour College">
            </a>
            <h1 class="auth-title">Welcome back</h1>
            <p class="auth-subtitle">Sign in to your account to continue</p>
        </div>

        <div class="auth-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        placeholder="you@example.com">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password" placeholder="Enter your password">
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember me for 30 days
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary auth-btn">
                    Sign In
                </button>

                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            Forgot your password?
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <div class="auth-footer">
            Don't have an account? <a href="{{ route('register') }}">Create one</a>
        </div>
    </div>
@endsection
