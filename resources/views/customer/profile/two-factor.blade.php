@extends('customer.layouts.app')

@section('title', 'Two-Factor Authentication')

@section('page-title', 'Two-Factor Authentication')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Secure Your Account</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <div class="d-flex">
                                <div>{{ session('success') }}</div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    @endif

                    @if (session('recovery_codes'))
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <h4 class="alert-title">Save Your Recovery Codes</h4>
                            <div class="text-secondary">Store these recovery codes in a secure location. They can be
                                used to access your account if you lose your 2FA device.</div>
                            <div class="mt-3 p-3 bg-white rounded font-monospace">
                                @foreach (session('recovery_codes') as $code)
                                    <div>{{ $code }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($user->two_factor_enabled)
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                                2FA Enabled
                            </h4>
                            <div class="text-secondary">Two-factor authentication is currently enabled on your
                                account.</div>
                        </div>

                        <div class="mb-3">
                            <a href="{{ route('customer.profile.recovery-codes') }}" class="btn btn-outline-primary">
                                View Recovery Codes
                            </a>
                        </div>

                        <hr>

                        <h3>Disable Two-Factor Authentication</h3>
                        <p class="text-secondary">This will remove the extra layer of security from your account.
                        </p>

                        <form method="POST" action="{{ route('customer.profile.two-factor.disable') }}"
                            onsubmit="return confirm('Are you sure you want to disable two-factor authentication?');">
                            @csrf
                            @method('DELETE')

                            <div class="mb-3">
                                <label class="form-label required">Confirm Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" required style="max-width: 300px;">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-danger">Disable 2FA</button>
                        </form>
                    @else
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    <polyline points="11 12 12 12 12 16 13 16"></polyline>
                                </svg>
                                2FA Not Enabled
                            </h4>
                            <div class="text-secondary">Add an extra layer of security to your account.</div>
                        </div>

                        <h3>Enable Two-Factor Authentication</h3>
                        <p class="text-secondary">Secure your account with two-factor authentication using Google
                            Authenticator or similar apps.</p>

                        <div class="steps steps-vertical mb-4">
                            <div class="step-item">
                                <div class="h4 m-0">Step 1</div>
                                <div class="text-secondary">Download an authenticator app like Google Authenticator,
                                    Authy, or Microsoft Authenticator</div>
                            </div>
                            <div class="step-item">
                                <div class="h4 m-0">Step 2</div>
                                <div class="text-secondary">Scan the QR code below with your authenticator app</div>
                            </div>
                            <div class="step-item">
                                <div class="h4 m-0">Step 3</div>
                                <div class="text-secondary">Enter the 6-digit code from the app to verify</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 text-center">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrCodeUrl) }}"
                                        alt="QR Code" class="img-fluid border rounded p-2" style="max-width: 250px;">
                                </div>

                                <div class="alert alert-light">
                                    <strong>Manual Entry Key:</strong>
                                    <div class="font-monospace mt-2">{{ $secret }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <form method="POST" action="{{ route('customer.profile.two-factor.enable') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label required">Verification Code</label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                            name="code" maxlength="6" pattern="[0-9]{6}" placeholder="000000"
                                            required>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">Enter the 6-digit code from your authenticator
                                            app</small>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Enable 2FA</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('customer.profile.edit') }}" class="btn btn-link">Back to Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
