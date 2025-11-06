@extends('customer.layouts.app')

@section('title', 'View Signature')

@section('page-title', $signature->title)

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h3 class="card-title mb-0">Signature Details</h3>
                        <div class="btn-group">
                            <a href="{{ route('customer.signatures.edit', $signature) }}" class="btn btn-primary">
                                <i class='bx bx-edit'></i> Edit
                            </a>
                            <a href="{{ route('customer.signatures.index') }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Title</label>
                                    <div class="fw-bold">{{ $signature->title }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Type</label>
                                    <div>
                                        @if ($signature->signature_type === 'image')
                                            <span class="badge bg-cyan text-white"><i class='bx bx-image'></i>
                                                Image</span>
                                        @else
                                            <span class="badge bg-teal text-white"><i class='bx bx-pen'></i>
                                                Draw</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Status</label>
                                    <div>
                                        @if ($signature->is_active)
                                            <span class="badge bg-success text-white"><i class='bx bx-check-circle'></i>
                                                Active</span>
                                        @else
                                            <span class="badge bg-danger text-white"><i class='bx bx-x-circle'></i>
                                                Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Created At</label>
                                    <div>{{ $signature->created_at->format('M d, Y H:i:s') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Signature Preview</label>
                                    <div class="border rounded p-4 bg-light text-center">
                                        @if ($signature->getSignatureFile())
                                            <img src="{{ $signature->signature_url }}" alt="{{ $signature->title }}"
                                                style="max-height: 200px; max-width: 100%; border: 1px solid #ddd; padding: 10px; background: white;">
                                        @else
                                            <p class="text-muted">No signature image available</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
