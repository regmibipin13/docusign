@extends('customer.layouts.app')

@section('title', 'Sign Document')

@section('page-title', 'Sign Document: ' . $document->name)

@section('content')
    <document-signing-app document-name="{{ $document->name }}" :document-id="{{ $document->id }}"
        document-url="{{ route('customer.documents.view', $document) }}"
        signatures-url="{{ route('customer.signing.signatures') }}"
        create-signature-url="{{ route('customer.signatures.create') }}"
        cancel-url="{{ route('customer.documents.show', $document) }}"></document-signing-app>
@endsection
