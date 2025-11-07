<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReceiverGroup;
use App\Models\ReceiverGroupMember;
use App\Models\User;
use Illuminate\Http\Request;

class ReceiverGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = ReceiverGroup::with('user')
            ->withCount('members')
            ->latest()
            ->paginate(15);

        return view('admin.receiver-groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('status', 'active')
            ->select('id', 'name', 'email')
            ->get();

        $allUsers = User::where('status', 'active')
            ->select('id', 'name', 'email')
            ->get();

        return view('admin.receiver-groups.create', compact('users', 'allUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
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
            'user_id' => $validated['user_id'],
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

        return redirect()->route('admin.receiver-groups.index')
            ->with('success', 'Receiver group created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ReceiverGroup $receiverGroup)
    {
        $receiverGroup->load(['user', 'members']);

        return view('admin.receiver-groups.show', compact('receiverGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReceiverGroup $receiverGroup)
    {
        $receiverGroup->load('members');

        $users = User::where('status', 'active')
            ->select('id', 'name', 'email')
            ->get();

        $allUsers = User::where('status', 'active')
            ->select('id', 'name', 'email')
            ->get();

        return view('admin.receiver-groups.edit', compact('receiverGroup', 'users', 'allUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReceiverGroup $receiverGroup)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
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
            'user_id' => $validated['user_id'],
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

        return redirect()->route('admin.receiver-groups.index')
            ->with('success', 'Receiver group updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReceiverGroup $receiverGroup)
    {
        $receiverGroup->delete();

        return redirect()->route('admin.receiver-groups.index')
            ->with('success', 'Receiver group deleted successfully!');
    }
}
