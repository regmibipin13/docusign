@extends('customer.layouts.app')

@section('title', 'Document Shares')

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Shares for: {{ $document->name }}</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('customer.documents.shares.create', $document) }}"
                                class="btn btn-primary btn-sm">
                                <i class='bx bx-plus'></i> New Share
                            </a>
                            <a href="{{ route('customer.documents.show', $document) }}" class="btn btn-secondary btn-sm">
                                <i class='bx bx-arrow-back'></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($shares->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Recipient</th>
                                            <th>Status</th>
                                            <th>Access Count</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shares as $share)
                                            <tr>
                                                <td>
                                                    @if ($share->share_type === 'public_link')
                                                        <span class="badge bg-info text-white">Public Link</span>
                                                    @elseif($share->share_type === 'email')
                                                        <span class="badge bg-success text-white">Email</span>
                                                    @else
                                                        <span class="badge bg-primary text-white">Registered User</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($share->share_type === 'email')
                                                        {{ $share->recipient_email }}
                                                    @elseif($share->share_type === 'registered_user')
                                                        {{ $share->sharedWith ? $share->sharedWith->name : 'N/A' }}
                                                    @else
                                                        <span class="text-muted">Anyone with link</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!$share->is_active)
                                                        <span class="badge bg-secondary text-white">Inactive</span>
                                                    @elseif($share->isExpired())
                                                        <span class="badge bg-danger text-white">Expired</span>
                                                    @else
                                                        <span class="badge bg-success text-white">Active</span>
                                                    @endif
                                                </td>
                                                <td>{{ $share->access_count }}</td>
                                                <td>{{ $share->created_at->diffForHumans() }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-info text-white copy-link-btn"
                                                            data-url="{{ $share->getShareUrl() }}">
                                                            <i class='bx bx-copy'></i> Copy Link
                                                        </button>
                                                        <form action="{{ route('customer.shares.destroy', $share) }}"
                                                            method="POST" onsubmit="return confirm('Revoke this share?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class='bx bx-trash'></i> Revoke
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class='bx bx-share' style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2">No shares created yet.</p>
                                <a href="{{ route('customer.documents.shares.create', $document) }}"
                                    class="btn btn-primary">
                                    <i class='bx bx-plus'></i> Create First Share
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Helper function to copy text with fallback
        function copyToClipboard(text) {
            // Try modern clipboard API first
            if (navigator.clipboard && window.isSecureContext) {
                return navigator.clipboard.writeText(text);
            } else {
                // Fallback for older browsers or non-secure contexts
                return new Promise((resolve, reject) => {
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-999999px';
                    textArea.style.top = '-999999px';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        textArea.remove();
                        resolve();
                    } catch (err) {
                        textArea.remove();
                        reject(err);
                    }
                });
            }
        }

        document.querySelectorAll('.copy-link-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                copyToClipboard(url).then(() => {
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<i class="bx bx-check"></i> Copied!';
                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                    }, 2000);
                }).catch(err => {
                    alert('Failed to copy link: ' + err.message);
                });
            });
        });
    </script>
@endsection
