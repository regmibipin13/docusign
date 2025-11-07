@extends('customer.layouts.app')

@section('title', 'Edit Receiver Group')

@section('page-title', 'Edit Receiver Group')

@section('content')
    <receiver-group-form mode="edit" form-action="{{ route('customer.receiver-groups.update', $receiverGroup) }}"
        back-url="{{ route('customer.receiver-groups.index') }}"
        cancel-url="{{ route('customer.receiver-groups.show', $receiverGroup) }}" :is-admin="false"
        :available-users="{{ $users->toJson() }}" initial-name="{{ old('name', $receiverGroup->name) }}"
        initial-description="{{ old('description', $receiverGroup->description) }}"
        :initial-emails="{{ json_encode($receiverGroup->emailMembers->pluck('recipient_value')->toArray()) }}"
        :initial-user-ids="{{ json_encode($receiverGroup->registeredUserMembers->pluck('recipient_value')->map(fn($id) => (int) $id)->toArray()) }}"
        :errors="{{ json_encode($errors->all()) }}" csrf-token="{{ csrf_token() }}"></receiver-group-form>
@endsection
