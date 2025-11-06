@extends('customer.layouts.app')

@section('title', 'My Signatures')

@section('page-title', 'My Signatures')

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h3 class="card-title mb-0">Signatures</h3>
                        <a href="{{ route('customer.signatures.create') }}" class="btn btn-primary">
                            <i class='bx bx-plus'></i> Create Signature
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('customer.signatures.index') }}" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by signature title..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class='bx bx-search'></i> Search
                                    </button>
                                </div>
                            </div>
                            @if (request('search'))
                                <div class="mt-2">
                                    <a href="{{ route('customer.signatures.index') }}" class="btn btn-sm btn-link">Clear
                                        Filters</a>
                                </div>
                            @endif
                        </form>

                        <!-- Signatures Table -->
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>Signature</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th class="w-1">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($signatures as $signature)
                                        <tr>
                                            <td>
                                                @if ($signature->getSignatureFile())
                                                    <img src="{{ $signature->signature_url }}" alt="{{ $signature->title }}"
                                                        style="max-height: 50px; max-width: 150px; border: 1px solid #ddd; padding: 5px;">
                                                @else
                                                    <span class="text-muted">No image</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $signature->title }}</div>
                                            </td>
                                            <td>
                                                @if ($signature->signature_type === 'image')
                                                    <span class="badge bg-cyan text-white"><i class='bx bx-image'></i>
                                                        Image</span>
                                                @else
                                                    <span class="badge bg-teal text-white"><i class='bx bx-pen'></i>
                                                        Draw</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($signature->is_active)
                                                    <span class="badge bg-success text-white"><i
                                                            class='bx bx-check-circle'></i>
                                                        Active</span>
                                                @else
                                                    <span class="badge bg-danger text-white"><i class='bx bx-x-circle'></i>
                                                        Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $signature->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('customer.signatures.show', $signature) }}"
                                                        class="btn btn-sm btn-primary" title="View">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                    <a href="{{ route('customer.signatures.edit', $signature) }}"
                                                        class="btn btn-sm btn-info" title="Edit">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                    {{-- <form
                                                        action="{{ route('customer.signatures.toggle-status', $signature) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm {{ $signature->is_active ? 'btn-warning' : 'btn-success' }}"
                                                            title="{{ $signature->is_active ? 'Deactivate' : 'Activate' }}">
                                                            <i
                                                                class='bx {{ $signature->is_active ? 'bx-toggle-right' : 'bx-toggle-left' }}'></i>
                                                            {{ $signature->is_active ? 'Disable' : 'Enable' }}
                                                        </button>
                                                    </form> --}}
                                                    <form action="{{ route('customer.signatures.destroy', $signature) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this signature?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class='bx bx-trash'></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class='bx bx-pen' style="font-size: 3rem; opacity: 0.3;"></i>
                                                <p class="text-muted mt-2">No signatures found</p>
                                                <a href="{{ route('customer.signatures.create') }}"
                                                    class="btn btn-primary btn-sm mt-2">
                                                    <i class='bx bx-plus'></i> Create Your First Signature
                                                </a>
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
