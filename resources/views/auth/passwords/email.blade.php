@extends('auth.layouts.auth')

@section('title', 'Reset Password - QuikSign')

@section('content')
    <div class="auth-card">
        <div class="auth-header">
            <a href="{{ route('frontend.home') }}" class="auth-logo">
                <img src="{{ asset('logo.png') }}" alt="QuikSign by Harbour College">
            </a>
            <h1 class="auth-title">Forgot password?</h1>
            <p class="auth-subtitle">Enter your email to receive a reset link</p>
        </div>

        <div class="auth-body">
            @if (session('status'))
                <div class="auth-alert auth-alert-success">
                    <i class='bx bx-check-circle'></i> {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
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
                    <div class="form-text">We'll send you a link to reset your password</div>
                </div>

                <button type="submit" class="btn btn-primary auth-btn">
                    Send Reset Link
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        <i class='bx bx-arrow-back'></i> Back to sign in
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
