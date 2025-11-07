@extends('customer.layouts.app')

@section('title', 'Create Receiver Group')

@section('page-title', 'Create Receiver Group')

@section('content')
    <receiver-group-form mode="create" form-action="{{ route('customer.receiver-groups.store') }}"
        back-url="{{ route('customer.receiver-groups.index') }}" cancel-url="{{ route('customer.receiver-groups.index') }}"
        :is-admin="false" :available-users="{{ $users->toJson() }}" :errors="{{ json_encode($errors->all()) }}"
        csrf-token="{{ csrf_token() }}"></receiver-group-form>
@endsection
