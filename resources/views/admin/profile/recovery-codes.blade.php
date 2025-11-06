@extends('admin.layouts.app')

@section('title', 'Recovery Codes')

@section('content')
    <div class="container-lg px-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recovery Codes</h5>
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
                                <h6 class="alert-heading">New Recovery Codes Generated</h6>
                                <p class="mb-0">Your old recovery codes are no longer valid. Save these new codes in a
                                    secure location.</p>
                            </div>
                        @endif

                        <p class="text-body-secondary">Store these recovery codes in a secure location. Each code can only
                            be used once.</p>

                        <div class="bg-light p-4 rounded font-monospace text-center mb-3">
                            @foreach (session('recovery_codes', $recoveryCodes) as $code)
                                <div class="mb-2">{{ $code }}</div>
                            @endforeach
                        </div>

                        <div class="alert alert-info">
                            <i class='bx bx-info-circle me-2'></i>
                            Use these codes if you lose access to your authenticator app. Each code can only be used once.
                        </div>

                        <hr>

                        <h6>Regenerate Recovery Codes</h6>
                        <p class="text-body-secondary">Generate new recovery codes. This will invalidate all previous codes.
                        </p>

                        <form method="POST" action="{{ route('admin.profile.recovery-codes.regenerate') }}"
                            onsubmit="return confirm('This will invalidate all your existing recovery codes. Continue?');">
                            @csrf

                            <div class="mb-3">
                                <label for="password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required style="max-width: 300px;">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">Regenerate Codes</button>
                                <a href="{{ route('admin.profile.two-factor') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
