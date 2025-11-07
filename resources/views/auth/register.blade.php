@extends('auth.layouts.auth')

@section('title', 'Create Account - QuikSign')

@section('content')
    <div class="auth-card">
        <div class="auth-header">
            <a href="{{ route('frontend.home') }}" class="auth-logo">
                <img src="{{ asset('logo.png') }}" alt="QuikSign by Harbour College">
            </a>
            <h1 class="auth-title">Create your account</h1>
            <p class="auth-subtitle">Get started with digital document signing</p>
        </div>

        <div class="auth-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                        name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                        placeholder="John Doe">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email"
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
                        name="password" required autocomplete="new-password" placeholder="Min. 8 characters">
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-text">Must be at least 8 characters long</div>
                </div>

                <div class="mb-3">
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                        autocomplete="new-password" placeholder="Re-enter your password">
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and
                            <a href="#" class="text-decoration-none">Privacy Policy</a>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary auth-btn">
                    Create Account
                </button>
            </form>
        </div>

        <div class="auth-footer">
            Already have an account? <a href="{{ route('login') }}">Sign in</a>
        </div>
    </div>
@endsection
