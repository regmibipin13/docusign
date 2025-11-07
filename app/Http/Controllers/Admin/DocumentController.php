<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Document::with(['user', 'signedDocuments']);

        // Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // User filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $documents = $query->latest()->paginate(15)->withQueryString();
        $users = User::where('role', 'customer')->orderBy('name')->get();

        return view('admin.documents.index', compact('documents', 'users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $document->load([
            'user',
            'signedDocuments.signature',
            'shares.sharedWith',
            'signedDocuments.shares.sharedWith'
        ]);
        return view('admin.documents.show', compact('document'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        // Media will be automatically deleted by Spatie Media Library
        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    /**
     * Download the document file.
     */
    public function download(Document $document)
    {
        $media = $document->getFirstMedia('documents');

        if (!$media) {
            abort(404, 'File not found.');
        }

        return response()->download($media->getPath(), $media->file_name);
    }

    /**
     * View the document PDF in browser.
     */
    public function view(Document $document)
    {
        $media = $document->getFirstMedia('documents');

        if (!$media) {
            abort(404, 'File not found.');
        }

        return response()->file($media->getPath(), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $media->file_name . '"'
        ]);
    }
}
