@extends('auth.layouts.auth')

@section('title', 'Reset Password - QuikSign')

@section('content')
    <div class="auth-card">
        <div class="auth-header">
            <a href="{{ route('frontend.home') }}" class="auth-logo">
                <img src="{{ asset('logo.png') }}" alt="QuikSign by Harbour College">
            </a>
            <h1 class="auth-title">Reset your password</h1>
            <p class="auth-subtitle">Enter your new password below</p>
        </div>

        <div class="auth-body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                        placeholder="you@example.com">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
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
                    <label for="password-confirm" class="form-label">Confirm New Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                        autocomplete="new-password" placeholder="Re-enter your password">
                </div>

                <button type="submit" class="btn btn-primary auth-btn">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
@endsection
