@extends('customer.layouts.app')

@section('title', 'Edit Profile')

@section('page-title', 'Edit Profile')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Profile Information</h3>
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

                    <form method="POST" action="{{ route('customer.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                                <a href="{{ route('customer.dashboard') }}" class="btn btn-link">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row row-cards mt-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Change Password</h3>
                            <p class="text-secondary">Update your password to keep your account secure.</p>
                            <a href="{{ route('customer.profile.password') }}" class="btn btn-primary">Change
                                Password</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Two-Factor Authentication</h3>
                            <p class="text-secondary mb-2">
                                Status:
                                @if ($user->two_factor_enabled)
                                    <span class="badge bg-success text-white">Enabled</span>
                                @else
                                    <span class="badge bg-secondary text-white">Disabled</span>
                                @endif
                            </p>
                            <a href="{{ route('customer.profile.two-factor') }}" class="btn btn-primary">Manage
                                2FA</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
