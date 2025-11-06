@extends('customer.layouts.app')

@section('title', 'View Document')

@section('page-title', $document->name)

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Document Details</h3>
                        <div class="btn-group">
                            @if ($document->isOriginal())
                                <a href="{{ route('customer.documents.sign', $document) }}" class="btn btn-warning">
                                    <i class='bx bx-pen'></i> Add Signature
                                </a>
                            @endif
                            <a href="{{ route('customer.documents.edit', $document) }}" class="btn btn-primary">
                                <i class='bx bx-edit'></i> Edit
                            </a>
                            <a href="{{ route('customer.documents.download', $document) }}" class="btn btn-success">
                                <i class='bx bx-download'></i> Download
                            </a>
                            <a href="{{ route('customer.documents.index') }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Back to List
                            </a>
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
                                        <div class="small">You can add more signatures to create additional signed
                                            documents</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($document->signedDocuments->count() > 0)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">
                                        <i class='bx bx-file-check'></i> Signed Documents
                                        ({{ $document->signedDocuments->count() }})
                                    </h4>
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
                                                                <img src="{{ $signedDoc->signature->signature_url }}"
                                                                    alt="{{ $signedDoc->signature->title }}"
                                                                    style="height: 40px; max-width: 150px;">
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $signedDoc->created_at->format('M d, Y H:i:s') }}</td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                @php
                                                                    $media = $signedDoc->getFirstMedia('signed_pdf');
                                                                @endphp
                                                                @if ($media)
                                                                    <a href="{{ $media->getUrl() }}"
                                                                        class="btn btn-sm btn-success"
                                                                        download="{{ $signedDoc->label }}.pdf">
                                                                        <i class='bx bx-download'></i> Download
                                                                    </a>
                                                                @endif
                                                                <form
                                                                    action="{{ route('customer.signed-documents.destroy', $signedDoc) }}"
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Name</label>
                                    <div class="fw-bold">{{ $document->name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">File Name</label>
                                    <div>{{ $document->file_name }}</div>
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
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Last Updated</label>
                                    <div>{{ $document->updated_at->format('M d, Y H:i:s') }}</div>
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

                <!-- PDF Viewer -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Document Preview</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="pdf-viewer-container" style="height: 800px; overflow: hidden;">
                            <iframe src="{{ route('customer.documents.view', $document) }}"
                                style="width: 100%; height: 100%; border: none;" title="Document Preview">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
