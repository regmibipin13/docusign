@extends('customer.layouts.app')

@section('title', 'View Receiver Group')

@section('page-title', 'Receiver Group Details')

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h3 class="card-title mb-0"><i class='bx bx-group'></i> {{ $receiverGroup->name }}</h3>
                        <div class="d-flex gap-2">
                            <a href="{{ route('customer.receiver-groups.edit', $receiverGroup) }}" class="btn btn-warning">
                                <i class='bx bx-edit'></i> Edit
                            </a>
                            <a href="{{ route('customer.receiver-groups.index') }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Group Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Group Information</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="150">Name:</th>
                                        <td>{{ $receiverGroup->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Description:</th>
                                        <td>{{ $receiverGroup->description ?: 'No description' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Members:</th>
                                        <td><span class="badge bg-info">{{ $receiverGroup->member_count }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td>{{ $receiverGroup->created_at->format('F d, Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated:</th>
                                        <td>{{ $receiverGroup->updated_at->format('F d, Y h:i A') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <!-- Members List -->
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-3">Group Members</h5>

                                @if ($receiverGroup->members->isEmpty())
                                    <div class="alert alert-warning">
                                        <i class='bx bx-info-circle'></i> No members in this group yet.
                                    </div>
                                @else
                                    <!-- Email Recipients -->
                                    @if ($receiverGroup->emailMembers->isNotEmpty())
                                        <div class="mb-4">
                                            <h6 class="text-muted"><i class='bx bx-envelope'></i> Email Recipients
                                                ({{ $receiverGroup->emailMembers->count() }})</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Email</th>
                                                            <th>Added On</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($receiverGroup->emailMembers as $index => $member)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>
                                                                    <i class='bx bx-envelope'></i>
                                                                    {{ $member->recipient_value }}
                                                                </td>
                                                                <td>{{ $member->created_at->format('M d, Y') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Registered Users -->
                                    @if ($receiverGroup->registeredUserMembers->isNotEmpty())
                                        <div class="mb-4">
                                            <h6 class="text-muted"><i class='bx bx-user-check'></i> Registered Users
                                                ({{ $receiverGroup->registeredUserMembers->count() }})</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Added On</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($receiverGroup->registeredUserMembers as $index => $member)
                                                            @php
                                                                $user = \App\Models\User::find(
                                                                    $member->recipient_value,
                                                                );
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>
                                                                    <i class='bx bx-user'></i>
                                                                    {{ $user ? $user->name : 'Unknown User' }}
                                                                </td>
                                                                <td>{{ $user ? $user->email : '-' }}</td>
                                                                <td>{{ $member->created_at->format('M d, Y') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <hr>
                        <div class="d-flex gap-2">
                            <a href="{{ route('customer.receiver-groups.edit', $receiverGroup) }}" class="btn btn-warning">
                                <i class='bx bx-edit'></i> Edit Group
                            </a>
                            <form action="{{ route('customer.receiver-groups.destroy', $receiverGroup) }}" method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this group? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class='bx bx-trash'></i> Delete Group
                                </button>
                            </form>
                            <a href="{{ route('customer.receiver-groups.index') }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
