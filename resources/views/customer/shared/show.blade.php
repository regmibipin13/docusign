@extends('customer.layouts.app')

@section('title', 'View Shared Document')

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class='bx bx-share-alt'></i> Shared Document
                        </h3>
                        <div class="btn-group">
                            <a href="{{ route('customer.shared-with-me.download', $share) }}" class="btn btn-success">
                                <i class='bx bx-download'></i> Download
                            </a>
                            <a href="{{ route('customer.shared-with-me.index') }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <i class='bx bx-info-circle' style="font-size: 2rem;"></i>
                                <div class="ms-3">
                                    <strong>Shared by {{ $share->sharedBy->name }}</strong>
                                    <div class="small">{{ $share->sharedBy->email }}</div>
                                    <div class="small mt-1">Shared on {{ $share->created_at->format('M d, Y \a\t H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (!empty($share->metadata['message']))
                            <div class="alert alert-secondary mb-4">
                                <strong>Message from sender:</strong>
                                <p class="mb-0 mt-2">{{ $share->metadata['message'] }}</p>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Document Name</label>
                                    @if ($share->signed_document_id)
                                        <div class="fw-semibold">{{ $share->signedDocument->label }}</div>
                                        <small class="text-muted">Signed Document</small>
                                    @else
                                        <div class="fw-semibold">{{ $share->document->name }}</div>
                                        <small class="text-muted">Original Document</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Document Type</label>
                                    <div>
                                        @if ($share->signed_document_id)
                                            <span class="badge bg-success text-white">
                                                <i class='bx bx-pen'></i> Signed Document
                                            </span>
                                        @else
                                            <span class="badge bg-info text-white">
                                                <i class='bx bx-file'></i> Original Document
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($share->expires_at)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Access Expires</label>
                                        <div>{{ $share->expires_at->format('M d, Y \a\t H:i') }}</div>
                                        <small class="text-muted">{{ $share->expires_at->diffForHumans() }}</small>
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
                            @php
                                if ($share->signed_document_id) {
                                    $media = $share->signedDocument?->getFirstMedia('signed_pdf');
                                } else {
                                    $media = $share->document?->getFirstMedia('documents');
                                }
                            @endphp
                            @if ($media)
                                <iframe src="{{ $media->getUrl() }}" style="width: 100%; height: 100%; border: none;"
                                    title="Document Preview">
                                </iframe>
                            @else
                                <div class="text-center py-5">
                                    <p class="text-muted">Document preview not available.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
