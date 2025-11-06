@extends('customer.layouts.app')

@section('title', 'My Documents')

@section('page-title', 'My Documents')

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h3 class="card-title mb-0">Documents</h3>
                        <a href="{{ route('customer.documents.create') }}" class="btn btn-primary">
                            <i class='bx bx-plus'></i> Upload Document
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('customer.documents.index') }}" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by document name..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class='bx bx-search'></i> Search
                                    </button>
                                </div>
                            </div>
                            @if (request('search'))
                                <div class="mt-2">
                                    <a href="{{ route('customer.documents.index') }}" class="btn btn-sm btn-link">Clear
                                        Filters</a>
                                </div>
                            @endif
                        </form>

                        <!-- Documents Table -->
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
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
                                                                <span class="badge bg-success text-white ms-2">
                                                                    <i class='bx bx-check-circle'></i>
                                                                    {{ $document->signedDocuments->count() }} Signed
                                                                </span>
                                                            @endif
                                                            @if ($document->originalDocument)
                                                                <span class="badge bg-info text-white ms-2"><i
                                                                        class='bx bx-link'></i>
                                                                    Signed Version</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-muted small">{{ $document->file_name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $document->file_size_human }}</td>
                                            <td>{{ $document->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('customer.documents.show', $document) }}"
                                                        class="btn btn-sm btn-primary" title="View">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                    @if ($document->isOriginal())
                                                        <a href="{{ route('customer.documents.sign', $document) }}"
                                                            class="btn btn-sm btn-warning" title="Add Signature">
                                                            <i class='bx bx-pen'></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('customer.documents.edit', $document) }}"
                                                        class="btn btn-sm btn-info" title="Edit">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                    <a href="{{ route('customer.documents.download', $document) }}"
                                                        class="btn btn-sm btn-success" title="Download">
                                                        <i class='bx bx-download'></i>
                                                    </a>
                                                    <form action="{{ route('customer.documents.destroy', $document) }}"
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
                                            <td colspan="4" class="text-center py-5">
                                                <i class='bx bx-file' style="font-size: 3rem; opacity: 0.3;"></i>
                                                <p class="text-muted mt-2">No documents found</p>
                                                <a href="{{ route('customer.documents.create') }}"
                                                    class="btn btn-primary">Upload Your First Document</a>
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
