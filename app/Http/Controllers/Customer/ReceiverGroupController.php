<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ReceiverGroup;
use App\Models\ReceiverGroupMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiverGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = ReceiverGroup::forUser(Auth::id())
            ->withCount('members')
            ->latest()
            ->paginate(10);

        return view('customer.receiver-groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())
            ->where('status', 'active')
            ->select('id', 'name', 'email')
            ->get();

        return view('customer.receiver-groups.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'emails' => 'nullable|array',
            'emails.*' => 'email',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        // Check if at least one recipient is provided
        if (empty($validated['emails']) && empty($validated['user_ids'])) {
            return back()->withErrors(['recipients' => 'Please add at least one email or registered user to the group.'])->withInput();
        }

        $group = ReceiverGroup::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        // Add email members
        if (!empty($validated['emails'])) {
            foreach ($validated['emails'] as $email) {
                ReceiverGroupMember::create([
                    'receiver_group_id' => $group->id,
                    'recipient_type' => 'email',
                    'recipient_value' => $email,
                ]);
            }
        }

        // Add registered user members
        if (!empty($validated['user_ids'])) {
            foreach ($validated['user_ids'] as $userId) {
                ReceiverGroupMember::create([
                    'receiver_group_id' => $group->id,
                    'recipient_type' => 'registered_user',
                    'recipient_value' => $userId,
                ]);
            }
        }

        return redirect()->route('customer.receiver-groups.index')
            ->with('success', 'Receiver group created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ReceiverGroup $receiverGroup)
    {
        // Authorization check
        if ($receiverGroup->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this group.');
        }

        $receiverGroup->load('members');

        return view('customer.receiver-groups.show', compact('receiverGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReceiverGroup $receiverGroup)
    {
        // Authorization check
        if ($receiverGroup->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this group.');
        }

        $receiverGroup->load('members');

        $users = User::where('id', '!=', Auth::id())
            ->where('status', 'active')
            ->select('id', 'name', 'email')
            ->get();

        return view('customer.receiver-groups.edit', compact('receiverGroup', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReceiverGroup $receiverGroup)
    {
        // Authorization check
        if ($receiverGroup->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this group.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'emails' => 'nullable|array',
            'emails.*' => 'email',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        // Check if at least one recipient is provided
        if (empty($validated['emails']) && empty($validated['user_ids'])) {
            return back()->withErrors(['recipients' => 'Please add at least one email or registered user to the group.'])->withInput();
        }

        $receiverGroup->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        // Delete old members
        $receiverGroup->members()->delete();

        // Add email members
        if (!empty($validated['emails'])) {
            foreach ($validated['emails'] as $email) {
                ReceiverGroupMember::create([
                    'receiver_group_id' => $receiverGroup->id,
                    'recipient_type' => 'email',
                    'recipient_value' => $email,
                ]);
            }
        }

        // Add registered user members
        if (!empty($validated['user_ids'])) {
            foreach ($validated['user_ids'] as $userId) {
                ReceiverGroupMember::create([
                    'receiver_group_id' => $receiverGroup->id,
                    'recipient_type' => 'registered_user',
                    'recipient_value' => $userId,
                ]);
            }
        }

        return redirect()->route('customer.receiver-groups.index')
            ->with('success', 'Receiver group updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReceiverGroup $receiverGroup)
    {
        // Authorization check
        if ($receiverGroup->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this group.');
        }

        $receiverGroup->delete();

        return redirect()->route('customer.receiver-groups.index')
            ->with('success', 'Receiver group deleted successfully!');
    }
}
