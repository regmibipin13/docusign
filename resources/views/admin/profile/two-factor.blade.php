@extends('admin.layouts.app')

@section('title', 'Two-Factor Authentication')

@section('content')
    <div class="container-lg px-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Two-Factor Authentication</h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-coreui-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('recovery_codes'))
                            <div class="alert alert-warning">
                                <h6 class="alert-heading">Save Your Recovery Codes</h6>
                                <p>Store these recovery codes in a secure location. They can be used to access your account
                                    if you lose your 2FA device.</p>
                                <div class="bg-light p-3 rounded font-monospace">
                                    @foreach (session('recovery_codes') as $code)
                                        <div>{{ $code }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($user->two_factor_enabled)
                            <div class="alert alert-success">
                                <i class='bx bxs-check-circle me-2'></i>
                                Two-factor authentication is <strong>enabled</strong> on your account.
                            </div>

                            <div class="mb-3">
                                <a href="{{ route('admin.profile.recovery-codes') }}" class="btn btn-outline-primary">
                                    View Recovery Codes
                                </a>
                            </div>

                            <hr>

                            <h6>Disable Two-Factor Authentication</h6>
                            <p class="text-body-secondary">This will remove the extra layer of security from your account.
                            </p>

                            <form method="POST" action="{{ route('admin.profile.two-factor.disable') }}"
                                onsubmit="return confirm('Are you sure you want to disable two-factor authentication?');">
                                @csrf
                                @method('DELETE')

                                <div class="mb-3">
                                    <label for="password" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required style="max-width: 300px;">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-danger">Disable 2FA</button>
                            </form>
                        @else
                            <div class="alert alert-info">
                                <i class='bx bx-info-circle me-2'></i>
                                Two-factor authentication is currently <strong>disabled</strong>.
                            </div>

                            <h6>Enable Two-Factor Authentication</h6>
                            <p class="text-body-secondary">Secure your account with two-factor authentication using Google
                                Authenticator or similar apps.</p>

                            <ol class="mb-4">
                                <li>Download an authenticator app like Google Authenticator, Authy, or Microsoft
                                    Authenticator</li>
                                <li>Scan the QR code below with your authenticator app</li>
                                <li>Enter the 6-digit code from the app to verify</li>
                            </ol>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 text-center">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrCodeUrl) }}"
                                            alt="QR Code" class="img-fluid border rounded p-2">
                                    </div>

                                    <div class="alert alert-light">
                                        <small class="text-muted">Manual Entry Key:</small>
                                        <div class="font-monospace">{{ $secret }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <form method="POST" action="{{ route('admin.profile.two-factor.enable') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="code" class="form-label">Verification Code</label>
                                            <input type="text" class="form-control @error('code') is-invalid @enderror"
                                                id="code" name="code" maxlength="6" pattern="[0-9]{6}"
                                                placeholder="000000" required>
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Enter the 6-digit code from your
                                                authenticator app</small>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Enable 2FA</button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('admin.profile.edit') }}" class="btn btn-secondary">Back to Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
