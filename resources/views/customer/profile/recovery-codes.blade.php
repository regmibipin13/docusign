@extends('customer.layouts.app')

@section('title', 'Recovery Codes')

@section('page-title', 'Recovery Codes')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Your Recovery Codes</h3>
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
                            <h4 class="alert-title">New Recovery Codes Generated</h4>
                            <div class="text-secondary">Your old recovery codes are no longer valid. Save these new
                                codes in a secure location.</div>
                        </div>
                    @endif

                    <p class="text-secondary">Store these recovery codes in a secure location. Each code can only be
                        used once.</p>

                    <div class="bg-light p-4 rounded font-monospace text-center mb-3">
                        @foreach (session('recovery_codes', $recoveryCodes) as $code)
                            <div class="mb-2">{{ $code }}</div>
                        @endforeach
                    </div>

                    <div class="alert alert-info" role="alert">
                        <h4 class="alert-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                <polyline points="11 12 12 12 12 16 13 16"></polyline>
                            </svg>
                            Important
                        </h4>
                        <div class="text-secondary">Use these codes if you lose access to your authenticator app.
                            Each code can only be used once.</div>
                    </div>

                    <hr>

                    <h3>Regenerate Recovery Codes</h3>
                    <p class="text-secondary">Generate new recovery codes. This will invalidate all previous codes.
                    </p>

                    <form method="POST" action="{{ route('customer.profile.recovery-codes.regenerate') }}"
                        onsubmit="return confirm('This will invalidate all your existing recovery codes. Continue?');">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label required">Confirm Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" required style="max-width: 300px;">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">Regenerate Codes</button>
                            <a href="{{ route('customer.profile.two-factor') }}" class="btn btn-link">Back</a>
                        </div>
                    </form>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
