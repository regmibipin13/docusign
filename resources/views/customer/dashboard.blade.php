@extends('customer.layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <div class="container-xl">
        <!-- Statistics Cards -->
        <div class="row row-deck row-cards mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Documents</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2">{{ $totalDocuments }}</div>
                            <div class="me-auto">
                                <span class="text-muted">documents</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Unsigned</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2">{{ $unsignedDocuments }}</div>
                            <div class="me-auto">
                                <span class="text-muted">awaiting</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Signed</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2">{{ $signedDocuments }}</div>
                            <div class="me-auto">
                                <span class="text-muted">completed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Signatures</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2">{{ $totalSignatures }}</div>
                            <div class="me-auto">
                                <span class="text-muted">available</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row row-deck row-cards mb-3">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class='bx bxs-file-pdf text-danger' style="font-size: 2.5rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-5">Upload Documents</div>
                                <div class="text-muted">Upload and manage your files</div>
                            </div>
                        </div>
                        <a href="{{ route('customer.documents.create') }}" class="btn btn-primary w-100">
                            <i class='bx bx-plus'></i> Upload New Document
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class='bx bx-pen text-primary' style="font-size: 2.5rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-5">Manage Signatures</div>
                                <div class="text-muted">Create and edit signatures</div>
                            </div>
                        </div>
                        <a href="{{ route('customer.signatures.index') }}" class="btn btn-primary w-100">
                            <i class='bx bx-show'></i> View Signatures
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class='bx bx-file-find text-success' style="font-size: 2.5rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-5">View Documents</div>
                                <div class="text-muted">Browse all your documents</div>
                            </div>
                        </div>
                        <a href="{{ route('customer.documents.index') }}" class="btn btn-primary w-100">
                            <i class='bx bx-folder-open'></i> Browse Documents
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Documents -->
        @if ($recentDocuments->count() > 0)
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h3 class="card-title mb-0">Recent Documents</h3>
                            <a href="{{ route('customer.documents.index') }}" class="btn btn-sm btn-primary">
                                View All
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Uploaded</th>
                                            <th class="w-1">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentDocuments as $document)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class='bx bxs-file-pdf text-danger me-2'
                                                            style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <div class="fw-bold">{{ $document->name }}</div>
                                                            <div class="text-muted small">{{ $document->file_name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($document->signedDocuments->count() > 0)
                                                        <span class="badge bg-success text-white">
                                                            <i class='bx bx-check-circle'></i>
                                                            {{ $document->signedDocuments->count() }} Signed
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">
                                                            <i class='bx bx-time'></i> Unsigned
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>{{ $document->created_at->format('M d, Y') }}</div>
                                                    <div class="text-muted small">
                                                        {{ $document->created_at->diffForHumans() }}</div>
                                                </td>
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
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Signatures -->
        @if ($recentSignatures->count() > 0)
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h3 class="card-title mb-0">Recent Signatures</h3>
                            <a href="{{ route('customer.signatures.index') }}" class="btn btn-sm btn-primary">
                                View All
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach ($recentSignatures as $signature)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card border">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div class="fw-bold">{{ $signature->title }}</div>
                                                    @if ($signature->is_active)
                                                        <span class="badge bg-success text-white">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary text-white">Inactive</span>
                                                    @endif
                                                </div>
                                                <div class="mb-2">
                                                    @if ($signature->getSignatureFile())
                                                        <img src="{{ $signature->signature_url }}"
                                                            alt="{{ $signature->title }}"
                                                            style="max-height: 60px; max-width: 100%;"
                                                            class="border rounded p-2">
                                                    @else
                                                        <span class="text-muted">No image</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted small mb-2">
                                                    Created {{ $signature->created_at->diffForHumans() }}
                                                </div>
                                                <div class="btn-group w-100">
                                                    <a href="{{ route('customer.signatures.show', $signature) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class='bx bx-show'></i> View
                                                    </a>
                                                    <a href="{{ route('customer.signatures.edit', $signature) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class='bx bx-edit'></i> Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Getting Started (only show if no documents) -->
        @if ($totalDocuments === 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Getting Started</h3>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span
                                            class="status-dot {{ $totalSignatures > 0 ? 'status-dot-animated bg-green' : 'bg-secondary' }} d-block"></span>
                                    </div>
                                    <div class="col text-truncate">
                                        <div class="text-body d-block fw-bold">Create your first signature</div>
                                        <div class="d-block text-muted text-truncate mt-n1">
                                            Start by creating a signature to use on documents
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <a href="{{ route('customer.signatures.create') }}"
                                            class="btn btn-sm btn-primary">
                                            <i class='bx bx-plus'></i> Create
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="status-dot bg-secondary d-block"></span>
                                    </div>
                                    <div class="col text-truncate">
                                        <div class="text-body d-block fw-bold">Upload your first document</div>
                                        <div class="d-block text-muted text-truncate mt-n1">
                                            Upload a PDF document to get started
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <a href="{{ route('customer.documents.create') }}"
                                            class="btn btn-sm btn-primary">
                                            <i class='bx bx-upload'></i> Upload
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
