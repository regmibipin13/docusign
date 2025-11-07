<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DocumentShare;
use Illuminate\Http\Request;

class SharedWithMeController extends Controller
{
    /**
     * Display all documents shared with the current user
     */
    public function index()
    {
        $shares = DocumentShare::where('shared_with_user_id', auth()->id())
            ->where('is_active', true)
            ->with(['document', 'signedDocument.signature', 'sharedBy'])
            ->latest()
            ->paginate(15);

        return view('customer.shared.index', compact('shares'));
    }

    /**
     * View a shared document
     */
    public function show(DocumentShare $share)
    {
        // Verify this share is for current user
        if ($share->shared_with_user_id !== auth()->id()) {
            abort(403, 'This document is not shared with you.');
        }

        if (!$share->is_active) {
            abort(403, 'This share has been revoked.');
        }

        if ($share->isExpired()) {
            abort(410, 'This share has expired.');
        }

        // Log access
        \App\Models\DocumentShareLog::create([
            'document_share_id' => $share->id,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'accessed_at' => now(),
        ]);

        $share->incrementAccessCount();

        $share->load(['document', 'signedDocument', 'sharedBy']);

        return view('customer.shared.show', compact('share'));
    }

    /**
     * Download a shared document
     */
    public function download(DocumentShare $share)
    {
        // Verify this share is for current user
        if ($share->shared_with_user_id !== auth()->id()) {
            abort(403, 'This document is not shared with you.');
        }

        if (!$share->is_active) {
            abort(403, 'This share has been revoked.');
        }

        if ($share->isExpired()) {
            abort(410, 'This share has expired.');
        }

        // Get the file
        $media = null;
        if ($share->signed_document_id) {
            $media = $share->signedDocument?->getFirstMedia('signed_pdf');
        } else {
            $media = $share->document?->getFirstMedia('documents');
        }

        if (!$media) {
            abort(404, 'File not found.');
        }

        return response()->download($media->getPath(), $media->file_name);
    }
}
