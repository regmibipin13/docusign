@extends('admin.layouts.app')

@section('title', 'Create Receiver Group')

@section('page-title', 'Create Receiver Group')

@section('content')
    <receiver-group-form mode="create" form-action="{{ route('admin.receiver-groups.store') }}"
        back-url="{{ route('admin.receiver-groups.index') }}" cancel-url="{{ route('admin.receiver-groups.index') }}"
        :is-admin="true" :owner-users="{{ $users->toJson() }}" :available-users="{{ $allUsers->toJson() }}"
        :errors="{{ json_encode($errors->all()) }}" csrf-token="{{ csrf_token() }}"></receiver-group-form>
@endsection
