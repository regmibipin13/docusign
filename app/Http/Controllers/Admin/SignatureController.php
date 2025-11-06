<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Signature;
use App\Models\User;
use Illuminate\Http\Request;

class SignatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Signature::with('user');

        // Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
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

        // Status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $signatures = $query->latest()->paginate(15)->withQueryString();
        $users = User::where('role', 'customer')->orderBy('name')->get();

        return view('admin.signatures.index', compact('signatures', 'users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Signature $signature)
    {
        $signature->load('user');
        return view('admin.signatures.show', compact('signature'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Signature $signature)
    {
        // Media will be automatically deleted by Spatie Media Library
        $signature->delete();

        return redirect()->route('admin.signatures.index')
            ->with('success', 'Signature deleted successfully.');
    }

    /**
     * Toggle signature active status.
     */
    public function toggleStatus(Signature $signature)
    {
        $signature->update([
            'is_active' => !$signature->is_active,
        ]);

        return redirect()->back()->with('success', 'Signature status updated successfully.');
    }
}
