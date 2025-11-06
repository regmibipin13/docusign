<?php

namespace App\Http\Controllers\Admin;

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
        $documentId = $signedDocument->document_id;

        // Media will be automatically deleted by Spatie Media Library
        $signedDocument->delete();

        return redirect()->route('admin.documents.show', $documentId)
            ->with('success', 'Signed document deleted successfully.');
    }
}
