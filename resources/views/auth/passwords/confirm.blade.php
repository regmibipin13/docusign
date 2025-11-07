@extends('auth.layouts.auth')

@section('title', 'Confirm Password - QuikSign')

@section('content')
    <div class="auth-card">
        <div class="auth-header">
            <a href="{{ route('frontend.home') }}" class="auth-logo">
                <img src="{{ asset('logo.png') }}" alt="QuikSign by Harbour College">
            </a>
            <h1 class="auth-title">Confirm password</h1>
            <p class="auth-subtitle">Please confirm your password to continue</p>
        </div>

        <div class="auth-body">
            <div class="text-center mb-4">
                <i class='bx bx-lock-alt' style="font-size: 4rem; color: var(--tblr-primary); opacity: 0.3;"></i>
            </div>

            <p class="text-center" style="color: var(--tblr-muted); margin-bottom: 1.5rem;">
                This is a secure area of the application. Please confirm your password before continuing.
            </p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

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

                <button type="submit" class="btn btn-primary auth-btn">
                    Confirm Password
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
    </div>
@endsection
