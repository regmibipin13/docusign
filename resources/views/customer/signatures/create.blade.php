@extends('customer.layouts.app')

@section('title', 'Create Signature')

@section('page-title', 'Create Signature')

@section('content')
    <signature-form-wrapper mode="create" form-action="{{ route('customer.signatures.store') }}"
        back-url="{{ route('customer.signatures.index') }}" cancel-url="{{ route('customer.signatures.index') }}"
        initial-signature-type="{{ old('signature_type', 'image') }}" initial-title="{{ old('title') }}"
        :initial-is-active="true" :errors="{{ json_encode($errors->all()) }}"
        csrf-token="{{ csrf_token() }}"></signature-form-wrapper>
@endsection
