@extends('admin.layouts.app')

@section('title', 'View Signature')

@section('content')
    <div class="container-lg px-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Signature Details</h5>
                            <div class="btn-group">
                                <a href="{{ route('admin.signatures.index') }}" class="btn btn-sm btn-secondary">
                                    <i class='bx bx-arrow-back'></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Signature ID</label>
                                    <div class="fw-semibold">{{ $signature->id }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Title</label>
                                    <div class="fw-semibold">{{ $signature->title }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Customer</label>
                                    <div>
                                        <div class="fw-semibold">{{ $signature->user->name }}</div>
                                        <div class="text-muted small">{{ $signature->user->email }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Type</label>
                                    <div>
                                        @if ($signature->signature_type === 'image')
                                            <span class="badge bg-info text-white"
                                                style="font-size: 1rem; padding: 0.5rem 0.75rem;"><i
                                                    class='bx bx-image'></i> Image</span>
                                        @else
                                            <span class="badge bg-success text-white"
                                                style="font-size: 1rem; padding: 0.5rem 0.75rem;"><i class='bx bx-pen'></i>
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
                                            <span class="badge bg-success text-white"
                                                style="font-size: 1rem; padding: 0.5rem 0.75rem;"><i
                                                    class='bx bx-check-circle'></i> Active</span>
                                        @else
                                            <span class="badge bg-danger text-white"
                                                style="font-size: 1rem; padding: 0.5rem 0.75rem;"><i
                                                    class='bx bx-x-circle'></i> Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Created At</label>
                                    <div>{{ $signature->created_at->format('M d, Y H:i:s') }}</div>
                                    <div class="text-muted small">{{ $signature->created_at->diffForHumans() }}</div>
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
                                                style="max-height: 250px; max-width: 100%; border: 1px solid #ddd; padding: 15px; background: white;">
                                        @else
                                            <p class="text-muted">No signature image available</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3">
                            <form action="{{ route('admin.signatures.toggle-status', $signature) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit"
                                    class="btn {{ $signature->is_active ? 'btn-warning' : 'btn-success' }}">
                                    <i class='bx {{ $signature->is_active ? 'bx-toggle-right' : 'bx-toggle-left' }}'></i>
                                    {{ $signature->is_active ? 'Deactivate' : 'Activate' }} Signature
                                </button>
                            </form>
                            <form action="{{ route('admin.signatures.destroy', $signature) }}" method="POST"
                                class="d-inline ms-2"
                                onsubmit="return confirm('Are you sure you want to delete this signature?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class='bx bx-trash'></i> Delete Signature
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
