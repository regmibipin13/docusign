@extends('admin.layouts.app')

@section('title', 'Manage Signatures')

@section('content')
    <div class="container-lg px-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">All Customer Signatures</h5>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('admin.signatures.index') }}" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by title, customer..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="user_id" class="form-select">
                                        <option value="">All Customers</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="is_active" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class='bx bx-search'></i> Filter
                                    </button>
                                </div>
                            </div>
                            @if (request('search') || request('user_id') || request('is_active'))
                                <div class="mt-2">
                                    <a href="{{ route('admin.signatures.index') }}" class="btn btn-sm btn-link">Clear
                                        Filters</a>
                                </div>
                            @endif
                        </form>

                        <!-- Signatures Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Signature</th>
                                        <th>Title</th>
                                        <th>Customer</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($signatures as $signature)
                                        <tr>
                                            <td>{{ $signature->id }}</td>
                                            <td>
                                                @if ($signature->getSignatureFile())
                                                    <img src="{{ $signature->signature_url }}"
                                                        alt="{{ $signature->title }}"
                                                        style="max-height: 40px; max-width: 120px; border: 1px solid #ddd; padding: 3px;">
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $signature->title }}</div>
                                            </td>
                                            <td>
                                                <div>{{ $signature->user->name }}</div>
                                                <div class="text-muted small">{{ $signature->user->email }}</div>
                                            </td>
                                            <td>
                                                @if ($signature->signature_type === 'image')
                                                    <span class="badge bg-info text-white"
                                                        style="font-size: 0.875rem; padding: 0.35rem 0.65rem;"><i
                                                            class='bx bx-image'></i> Image</span>
                                                @else
                                                    <span class="badge bg-success text-white"
                                                        style="font-size: 0.875rem; padding: 0.35rem 0.65rem;"><i
                                                            class='bx bx-pen'></i> Draw</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($signature->is_active)
                                                    <span class="badge bg-success text-white"
                                                        style="font-size: 0.875rem; padding: 0.35rem 0.65rem;"><i
                                                            class='bx bx-check-circle'></i> Active</span>
                                                @else
                                                    <span class="badge bg-danger text-white"
                                                        style="font-size: 0.875rem; padding: 0.35rem 0.65rem;"><i
                                                            class='bx bx-x-circle'></i> Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $signature->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.signatures.show', $signature) }}"
                                                        class="btn btn-sm btn-ghost-primary" title="View">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('admin.signatures.toggle-status', $signature) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm {{ $signature->is_active ? 'btn-warning' : 'btn-success' }}"
                                                            title="{{ $signature->is_active ? 'Deactivate' : 'Activate' }}">
                                                            <i
                                                                class='bx {{ $signature->is_active ? 'bx-toggle-right' : 'bx-toggle-left' }}'></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.signatures.destroy', $signature) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this signature?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-ghost-danger"
                                                            title="Delete">
                                                            <i class='bx bx-trash'></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <i class='bx bx-pen' style="font-size: 3rem; opacity: 0.3;"></i>
                                                <p class="text-muted mt-2">No signatures found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($signatures->hasPages())
                            <div class="mt-4">
                                {{ $signatures->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
