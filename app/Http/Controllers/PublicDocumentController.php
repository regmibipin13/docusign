<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentShare;
use App\Models\DocumentShareLog;
use Illuminate\Http\Request;

class PublicDocumentController extends Controller
{
    public function show($token)
    {
        $share = DocumentShare::where('share_token', $token)->where('is_active', true)->firstOrFail();

        if ($share->isExpired()) {
            abort(410, 'This share link has expired.');
        }

        // Log access
        DocumentShareLog::create([
            'document_share_id' => $share->id,
            'user_id' => auth()->check() ? auth()->id() : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'accessed_at' => now(),
        ]);

        $share->incrementAccessCount();

        // Determine media
        $media = null;
        if ($share->signed_document_id) {
            $media = $share->signedDocument?->getFirstMedia('signed_pdf');
        }

        if (!$media && $share->document_id) {
            $media = $share->document?->getFirstMedia('documents');
        }

        if (!$media) {
            abort(404, 'File not found.');
        }

        return response()->file($media->getPath(), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $media->file_name . '"',
        ]);
    }

    public function download($token)
    {
        $share = DocumentShare::where('share_token', $token)->where('is_active', true)->firstOrFail();

        if ($share->isExpired()) {
            abort(410, 'This share link has expired.');
        }

        // Log access
        DocumentShareLog::create([
            'document_share_id' => $share->id,
            'user_id' => auth()->check() ? auth()->id() : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'accessed_at' => now(),
        ]);

        $share->incrementAccessCount();

        $media = null;
        if ($share->signed_document_id) {
            $media = $share->signedDocument?->getFirstMedia('signed_pdf');
        }

        if (!$media && $share->document_id) {
            $media = $share->document?->getFirstMedia('documents');
        }

        if (!$media) {
            abort(404, 'File not found.');
        }

        return response()->download($media->getPath(), $media->file_name);
    }
}
