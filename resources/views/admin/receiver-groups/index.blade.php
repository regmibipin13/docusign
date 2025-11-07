@extends('admin.layouts.app')

@section('title', 'Receiver Groups')

@section('page-title', 'Receiver Groups')

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h3 class="card-title mb-0"><i class='bx bx-group'></i> All Receiver Groups</h3>
                        <a href="{{ route('admin.receiver-groups.create') }}" class="btn btn-primary">
                            <i class='bx bx-plus'></i> Create New Group
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($groups->isEmpty())
                            <div class="text-center py-5">
                                <i class='bx bx-group' style="font-size: 4rem; color: #ccc;"></i>
                                <h4 class="mt-3">No Receiver Groups Yet</h4>
                                <p class="text-muted">Create groups to manage document sharing recipients.</p>
                                <a href="{{ route('admin.receiver-groups.create') }}" class="btn btn-primary">
                                    <i class='bx bx-plus'></i> Create First Group
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Owner</th>
                                            <th>Description</th>
                                            <th>Members</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($groups as $group)
                                            <tr>
                                                <td>{{ $group->id }}</td>
                                                <td>
                                                    <strong>{{ $group->name }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ $group->user->name }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ Str::limit($group->description, 40) ?: '-' }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $group->members_count }} member(s)</span>
                                                </td>
                                                <td>{{ $group->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.receiver-groups.show', $group) }}"
                                                            class="btn btn-sm btn-info" title="View">
                                                            <i class='bx bx-show'></i>
                                                        </a>
                                                        <a href="{{ route('admin.receiver-groups.edit', $group) }}"
                                                            class="btn btn-sm btn-warning" title="Edit">
                                                            <i class='bx bx-edit'></i>
                                                        </a>
                                                        <form action="{{ route('admin.receiver-groups.destroy', $group) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this group?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                title="Delete">
                                                                <i class='bx bx-trash'></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-3">
                                {{ $groups->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
