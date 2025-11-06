<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SignedDocument;
use Illuminate\Http\Request;

class SignedDocumentController extends Controller
{
    /**
     * Remove the specified signed document from storage.
     */
    public function destroy(SignedDocument $signedDocument)
    {
        // Ensure user can only delete their own signed documents
        if ($signedDocument->document->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $documentId = $signedDocument->document_id;

        // Media will be automatically deleted by Spatie Media Library
        $signedDocument->delete();

        return redirect()->route('customer.documents.show', $documentId)
            ->with('success', 'Signed document deleted successfully.');
    }
}
