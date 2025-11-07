<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->documents();

        // Search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $documents = $query->latest()->paginate(10)->withQueryString();

        return view('customer.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.documents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'file' => ['required', File::types(['pdf'])->max(10 * 1024)], // 10MB max
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $document = Document::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Add file to media library
        $document->addMedia($request->file('file'))
            ->usingFileName(time() . '_' . $request->file('file')->getClientOriginalName())
            ->toMediaCollection('documents');

        return redirect()->route('customer.documents.index')
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        // Ensure user can only view their own documents
        if ($document->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load relationships
        $document->load([
            'signedVersions',
            'signedDocuments.signature',
            'originalDocument',
            'shares.sharedWith',
            'signedDocuments.shares.sharedWith'
        ]);

        return view('customer.documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        // Ensure user can only edit their own documents
        if ($document->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('customer.documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        // Ensure user can only update their own documents
        if ($document->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'file' => ['nullable', File::types(['pdf'])->max(10 * 1024)], // 10MB max
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $document->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // If new file is uploaded
        if ($request->hasFile('file')) {
            // Delete old media and add new one
            $document->clearMediaCollection('documents');

            $document->addMedia($request->file('file'))
                ->usingFileName(time() . '_' . $request->file('file')->getClientOriginalName())
                ->toMediaCollection('documents');
        }

        return redirect()->route('customer.documents.index')
            ->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        // Ensure user can only delete their own documents
        if ($document->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Media will be automatically deleted by Spatie Media Library
        $document->delete();

        return redirect()->route('customer.documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    /**
     * Download the document file.
     */
    public function download(Document $document)
    {
        // Ensure user can only download their own documents
        if ($document->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

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
        // Ensure user can only view their own documents
        if ($document->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

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
