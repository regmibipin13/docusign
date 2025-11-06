@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
    <div class="container-lg px-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">User Details</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">
                                <i class='bx bx-edit icon-xs'></i>
                                Edit
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
                                <i class='bx bx-arrow-back icon-xs'></i>
                                Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">ID</label>
                                    <div class="fw-semibold">{{ $user->id }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Name</label>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email</label>
                                    <div class="fw-semibold">{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email Verified</label>
                                    <div>
                                        @if ($user->email_verified_at)
                                            <span class="badge bg-success">Verified</span>
                                            <span class="text-muted small">
                                                ({{ $user->email_verified_at->format('M d, Y') }})
                                            </span>
                                        @else
                                            <span class="badge bg-warning">Not Verified</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Role</label>
                                    <div>
                                        @if ($user->role === 'admin')
                                            <span class="badge bg-danger">Admin</span>
                                        @else
                                            <span class="badge bg-primary">Customer</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Status</label>
                                    <div>
                                        @if ($user->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Two-Factor Authentication</label>
                                    <div>
                                        @if ($user->two_factor_confirmed_at)
                                            <span class="badge bg-success">
                                                <i class='bx bxs-shield-alt-2 icon-xs'></i>
                                                Enabled
                                            </span>
                                            <span class="text-muted small">
                                                ({{ \Carbon\Carbon::parse($user->two_factor_confirmed_at)->format('M d, Y') }})
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class='bx bxs-shield-x icon-xs'></i>
                                                Disabled
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Member Since</label>
                                    <div class="fw-semibold">{{ $user->created_at->format('M d, Y') }}</div>
                                    <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Last Updated</label>
                                    <div class="fw-semibold">{{ $user->updated_at->format('M d, Y H:i:s') }}</div>
                                    <div class="text-muted small">{{ $user->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>

                        @if ($user->id !== auth()->id())
                            <div class="border-top pt-4 mt-4">
                                <h6 class="mb-3">Actions</h6>
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">
                                            @if ($user->status === 'active')
                                                <i class='bx bx-lock icon-xs'></i>
                                                Disable User
                                            @else
                                                <i class='bx bx-lock-open icon-xs'></i>
                                                Enable User
                                            @endif
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class='bx bx-trash icon-xs'></i>
                                            Delete User
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class='bx bx-info-circle me-2'></i>
                                This is your account. You cannot disable or delete your own account.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
