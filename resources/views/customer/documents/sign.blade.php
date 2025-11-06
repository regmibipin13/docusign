@extends('customer.layouts.app')

@section('title', 'Sign Document')

@section('page-title', 'Sign Document: ' . $document->name)

@section('content')
    <div id="signApp" data-document-name="{{ $document->name }}" data-document-id="{{ $document->id }}"
        data-document-url="{{ route('customer.documents.view', $document) }}"
        data-signatures-url="{{ route('customer.signing.signatures') }}"
        data-create-signature-url="{{ route('customer.signatures.create') }}"
        data-cancel-url="{{ route('customer.documents.show', $document) }}">
    </div>
@endsection
