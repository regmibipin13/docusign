@extends('admin.layouts.app')

@section('title', 'Edit Receiver Group')

@section('page-title', 'Edit Receiver Group')

@section('content')
    <receiver-group-form mode="edit" form-action="{{ route('admin.receiver-groups.update', $receiverGroup) }}"
        back-url="{{ route('admin.receiver-groups.index') }}"
        cancel-url="{{ route('admin.receiver-groups.show', $receiverGroup) }}" :is-admin="true"
        :owner-users="{{ $users->toJson() }}" :available-users="{{ $allUsers->toJson() }}"
        initial-name="{{ old('name', $receiverGroup->name) }}"
        initial-description="{{ old('description', $receiverGroup->description) }}"
        :initial-owner-id="{{ $receiverGroup->user_id }}"
        :initial-emails="{{ json_encode($receiverGroup->emailMembers->pluck('recipient_value')->toArray()) }}"
        :initial-user-ids="{{ json_encode($receiverGroup->registeredUserMembers->pluck('recipient_value')->map(fn($id) => (int) $id)->toArray()) }}"
        :errors="{{ json_encode($errors->all()) }}" csrf-token="{{ csrf_token() }}"></receiver-group-form>
@endsection
