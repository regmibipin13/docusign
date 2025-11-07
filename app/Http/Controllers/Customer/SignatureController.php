<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SignatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->signatures();

        // Search filter
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $signatures = $query->latest()->paginate(10)->withQueryString();

        return view('customer.signatures.index', compact('signatures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.signatures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'signature_type' => ['required', 'in:image,draw'],
            'signature_image' => ['required_if:signature_type,image', 'image', 'mimes:png,jpg,jpeg,gif', 'max:2048'],
            'signature_data' => ['required_if:signature_type,draw', 'string', 'min:10'],
            'is_active' => ['boolean'],
        ]);

        $signature = Signature::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'signature_type' => $request->signature_type,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Handle signature upload
        if ($request->signature_type === 'image' && $request->hasFile('signature_image')) {
            $signature->addMedia($request->file('signature_image'))
                ->usingFileName(time() . '_' . $request->file('signature_image')->getClientOriginalName())
                ->toMediaCollection('signatures');
        } elseif ($request->signature_type === 'draw' && $request->signature_data) {
            // Convert base64 to image
            $imageData = $request->signature_data;
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageData = base64_decode($imageData);

            $fileName = 'signature_' . time() . '.png';
            $tempPath = sys_get_temp_dir() . '/' . $fileName;
            file_put_contents($tempPath, $imageData);

            $signature->addMedia($tempPath)
                ->usingFileName($fileName)
                ->toMediaCollection('signatures');

            // Clean up temp file if it still exists
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }

        return redirect()->route('customer.signatures.index')
            ->with('success', 'Signature created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Signature $signature)
    {
        // Ensure user can only view their own signatures
        if ($signature->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('customer.signatures.show', compact('signature'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Signature $signature)
    {
        // Ensure user can only edit their own signatures
        if ($signature->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('customer.signatures.edit', compact('signature'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Signature $signature)
    {
        // Ensure user can only update their own signatures
        if ($signature->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'signature_type' => ['required', 'in:image,draw'],
            'signature_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,gif', 'max:2048'],
            'signature_data' => ['nullable', 'string', 'min:10'],
            'is_active' => ['boolean'],
        ]);

        $signature->update([
            'title' => $request->title,
            'signature_type' => $request->signature_type,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Handle signature replacement
        if ($request->signature_type === 'image' && $request->hasFile('signature_image')) {
            $signature->clearMediaCollection('signatures');

            $signature->addMedia($request->file('signature_image'))
                ->usingFileName(time() . '_' . $request->file('signature_image')->getClientOriginalName())
                ->toMediaCollection('signatures');
        } elseif ($request->signature_type === 'draw' && $request->signature_data) {
            $signature->clearMediaCollection('signatures');

            // Convert base64 to image
            $imageData = $request->signature_data;
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageData = base64_decode($imageData);

            $fileName = 'signature_' . time() . '.png';
            $tempPath = sys_get_temp_dir() . '/' . $fileName;
            file_put_contents($tempPath, $imageData);

            $signature->addMedia($tempPath)
                ->usingFileName($fileName)
                ->toMediaCollection('signatures');

            // Clean up temp file if it still exists
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }

        return redirect()->route('customer.signatures.index')
            ->with('success', 'Signature updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Signature $signature)
    {
        // Ensure user can only delete their own signatures
        if ($signature->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Media will be automatically deleted by Spatie Media Library
        $signature->delete();

        return redirect()->route('customer.signatures.index')
            ->with('success', 'Signature deleted successfully.');
    }

    /**
     * Toggle signature active status.
     */
    public function toggleStatus(Signature $signature)
    {
        // Ensure user can only toggle their own signatures
        if ($signature->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $signature->update([
            'is_active' => !$signature->is_active,
        ]);

        return redirect()->back()->with('success', 'Signature status updated successfully.');
    }
}
