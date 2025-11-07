@extends('auth.layouts.auth')

@section('title', 'Verify Email - QuikSign')

@section('content')
    <div class="auth-card">
        <div class="auth-header">
            <a href="{{ route('frontend.home') }}" class="auth-logo">
                <img src="{{ asset('logo.png') }}" alt="QuikSign by Harbour College">
            </a>
            <h1 class="auth-title">Verify your email</h1>
            <p class="auth-subtitle">We've sent you a verification link</p>
        </div>

        <div class="auth-body">
            @if (session('resent'))
                <div class="auth-alert auth-alert-success">
                    <i class='bx bx-check-circle'></i> A fresh verification link has been sent to your email address.
                </div>
            @endif

            <div class="text-center mb-4">
                <i class='bx bx-envelope' style="font-size: 4rem; color: var(--tblr-primary); opacity: 0.3;"></i>
            </div>

            <p class="text-center" style="color: var(--tblr-muted); margin-bottom: 1.5rem;">
                Before proceeding, please check your email for a verification link. If you didn't receive the email,
                you can request a new one below.
            </p>

            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-outline-primary auth-btn">
                    <i class='bx bx-envelope'></i> Resend Verification Email
                </button>
            </form>

            <div class="text-center mt-3">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none p-0">
                        <i class='bx bx-log-out'></i> Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
