@extends('admin.layouts.app')

@section('title', 'Manage Documents')

@section('content')
    <div class="container-lg px-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">All Customer Documents</h5>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('admin.documents.index') }}" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by name, customer..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-4">
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
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class='bx bx-search'></i> Filter
                                    </button>
                                </div>
                            </div>
                            @if (request('search') || request('user_id'))
                                <div class="mt-2">
                                    <a href="{{ route('admin.documents.index') }}" class="btn btn-sm btn-link">Clear
                                        Filters</a>
                                </div>
                            @endif
                        </form>

                        <!-- Documents Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Customer</th>
                                        <th>File Size</th>
                                        <th>Uploaded</th>
                                        <th class="w-1">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($documents as $document)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class='bx bxs-file-pdf text-danger me-2'
                                                        style="font-size: 1.5rem;"></i>
                                                    <div>
                                                        <div class="fw-bold">
                                                            {{ $document->name }}
                                                            @if ($document->signedDocuments->count() > 0)
                                                                <span class="badge bg-success ms-2">
                                                                    <i class='bx bx-check-circle'></i>
                                                                    {{ $document->signedDocuments->count() }} Signed
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="text-muted small">{{ $document->file_name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>{{ $document->user->name }}</div>
                                                <div class="text-muted small">{{ $document->user->email }}</div>
                                            </td>
                                            <td>{{ $document->file_size_human }}</td>
                                            <td>
                                                <div>{{ $document->created_at->format('M d, Y') }}</div>
                                                <div class="text-muted small">{{ $document->created_at->diffForHumans() }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.documents.show', $document) }}"
                                                        class="btn btn-sm btn-primary" title="View">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                    <a href="{{ route('admin.documents.download', $document) }}"
                                                        class="btn btn-sm btn-success" title="Download">
                                                        <i class='bx bx-download'></i>
                                                    </a>
                                                    <form action="{{ route('admin.documents.destroy', $document) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this document?');">
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
                                            <td colspan="5" class="text-center py-5">
                                                <i class='bx bx-file' style="font-size: 3rem; opacity: 0.3;"></i>
                                                <p class="text-muted mt-2">No documents found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($documents->hasPages())
                            <div class="mt-4">
                                {{ $documents->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
