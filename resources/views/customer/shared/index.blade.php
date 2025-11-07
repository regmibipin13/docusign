@extends('customer.layouts.app')

@section('title', 'Shared With Me')

@section('page-title', 'Shared With Me')

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class='bx bx-share-alt'></i> Documents Shared With Me
                        </h3>
                        <span class="badge bg-primary text-white">{{ $shares->total() }} total</span>
                    </div>
                    <div class="card-body">
                        @if ($shares->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Document</th>
                                            <th>Shared By</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Shared On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shares as $share)
                                            <tr>
                                                <td>
                                                    @if ($share->signed_document_id)
                                                        <div>
                                                            <div class="fw-semibold">{{ $share->signedDocument->label }}
                                                            </div>
                                                            <small class="text-muted">
                                                                <i class='bx bx-file-check'></i> Signed Document
                                                            </small>
                                                        </div>
                                                    @else
                                                        <div>
                                                            <div class="fw-semibold">{{ $share->document->name }}</div>
                                                            <small class="text-muted">
                                                                <i class='bx bx-file'></i> Original Document
                                                            </small>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold">{{ $share->sharedBy->name }}</div>
                                                        <small class="text-muted">{{ $share->sharedBy->email }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($share->signed_document_id)
                                                        <span class="badge bg-success text-white">
                                                            <i class='bx bx-pen'></i> Signed
                                                        </span>
                                                    @else
                                                        <span class="badge bg-info text-white">
                                                            <i class='bx bx-file'></i> Original
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!$share->is_active)
                                                        <span class="badge bg-secondary text-white">Revoked</span>
                                                    @elseif($share->isExpired())
                                                        <span class="badge bg-danger text-white">Expired</span>
                                                    @else
                                                        <span class="badge bg-success text-white">Active</span>
                                                    @endif
                                                    @if ($share->expires_at)
                                                        <div class="small text-muted mt-1">
                                                            Expires: {{ $share->expires_at->format('M d, Y') }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>{{ $share->created_at->format('M d, Y') }}</div>
                                                    <small
                                                        class="text-muted">{{ $share->created_at->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        @if ($share->is_active && !$share->isExpired())
                                                            <a href="{{ route('customer.shared-with-me.show', $share) }}"
                                                                class="btn btn-sm btn-primary">
                                                                <i class='bx bx-show'></i> View
                                                            </a>
                                                            <a href="{{ route('customer.shared-with-me.download', $share) }}"
                                                                class="btn btn-sm btn-success">
                                                                <i class='bx bx-download'></i> Download
                                                            </a>
                                                        @else
                                                            <button class="btn btn-sm btn-secondary" disabled>
                                                                <i class='bx bx-lock'></i> Unavailable
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $shares->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class='bx bx-inbox' style="font-size: 4rem; color: #ccc;"></i>
                                <h5 class="mt-3 text-muted">No Documents Shared With You</h5>
                                <p class="text-muted">When someone shares a document with you, it will appear here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
