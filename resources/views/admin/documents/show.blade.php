@extends('admin.layouts.app')

@section('title', 'View Document')

@section('content')
    <div class="container-lg px-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Document Details</h5>
                            <div class="btn-group">
                                <a href="{{ route('admin.documents.download', $document) }}" class="btn btn-sm btn-success">
                                    <i class='bx bx-download'></i> Download
                                </a>
                                <a href="{{ route('admin.documents.index') }}" class="btn btn-sm btn-secondary">
                                    <i class='bx bx-arrow-back'></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($document->signedDocuments->count() > 0)
                            <div class="alert alert-success mb-4">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-check-circle' style="font-size: 2rem;"></i>
                                    <div class="ms-3">
                                        <strong>This document has {{ $document->signedDocuments->count() }} signed
                                            version(s)</strong>
                                        <div class="small">View all signed documents below</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Document Name</label>
                                    <div class="fw-semibold">{{ $document->name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">File Name</label>
                                    <div>{{ $document->file_name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Customer</label>
                                    <div>
                                        <div class="fw-semibold">{{ $document->user->name }}</div>
                                        <div class="text-muted small">{{ $document->user->email }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">File Size</label>
                                    <div>{{ $document->file_size_human }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Uploaded At</label>
                                    <div>{{ $document->created_at->format('M d, Y H:i:s') }}</div>
                                    <div class="text-muted small">{{ $document->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Last Updated</label>
                                    <div>{{ $document->updated_at->format('M d, Y H:i:s') }}</div>
                                    <div class="text-muted small">{{ $document->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                        @if ($document->description)
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Description</label>
                                        <div>{{ $document->description }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($document->signedDocuments->count() > 0)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class='bx bx-file-check'></i> Signed Documents
                                ({{ $document->signedDocuments->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Label</th>
                                            <th>Signature Used</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($document->signedDocuments as $signedDoc)
                                            <tr>
                                                <td>
                                                    <strong>{{ $signedDoc->label }}</strong>
                                                </td>
                                                <td>
                                                    @if ($signedDoc->signature)
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $signedDoc->signature->signature_url }}"
                                                                alt="{{ $signedDoc->signature->title }}"
                                                                style="height: 40px; max-width: 150px;"
                                                                class="border rounded p-1 me-2">
                                                            <div>
                                                                <div class="fw-semibold">{{ $signedDoc->signature->title }}
                                                                </div>
                                                                <div class="text-muted small">
                                                                    {{ ucfirst($signedDoc->signature->signature_type) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>{{ $signedDoc->created_at->format('M d, Y H:i:s') }}</div>
                                                    <div class="text-muted small">
                                                        {{ $signedDoc->created_at->diffForHumans() }}</div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @php
                                                            $media = $signedDoc->getFirstMedia('signed_pdf');
                                                        @endphp
                                                        @if ($media)
                                                            <a href="{{ $media->getUrl() }}" class="btn btn-sm btn-success"
                                                                download="{{ $signedDoc->label }}.pdf">
                                                                <i class='bx bx-download'></i> Download
                                                            </a>
                                                        @endif
                                                        <form
                                                            action="{{ route('admin.signed-documents.destroy', $signedDoc) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this signed document? This action cannot be undone.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class='bx bx-trash'></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- PDF Viewer -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Document Preview</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="pdf-viewer-container" style="height: 800px; overflow: hidden;">
                            <iframe src="{{ route('admin.documents.view', $document) }}"
                                style="width: 100%; height: 100%; border: none;" title="Document Preview">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
