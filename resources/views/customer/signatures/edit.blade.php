@extends('customer.layouts.app')

@section('title', 'Edit Signature')

@section('page-title', 'Edit Signature')

@section('content')
    <signature-form-wrapper mode="edit" form-action="{{ route('customer.signatures.update', $signature) }}"
        back-url="{{ route('customer.signatures.index') }}" cancel-url="{{ route('customer.signatures.show', $signature) }}"
        initial-signature-type="{{ old('signature_type', $signature->signature_type) }}"
        initial-title="{{ old('title', $signature->title) }}"
        :initial-is-active="{{ old('is_active', $signature->is_active) ? 'true' : 'false' }}"
        current-signature-url="{{ $signature->getSignatureFile() ? $signature->signature_url : '' }}"
        :errors="{{ json_encode($errors->all()) }}" csrf-token="{{ csrf_token() }}"></signature-form-wrapper>
@endsection
