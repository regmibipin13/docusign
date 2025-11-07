@extends('customer.layouts.app')

@section('title', 'Share Document')

@section('content')
    <share-document-form document-name="{{ $document->name }}"
        form-action="{{ route('customer.documents.shares.store', $document) }}"
        back-url="{{ route('customer.documents.show', $document) }}" :users="{{ $users->toJson() }}"
        :receiver-groups="{{ $receiverGroups->toJson() }}" manage-groups-url="{{ route('customer.receiver-groups.index') }}"
        share-link="{{ session('share_link') ?? '' }}"
        success-message="{{ session('success') ?? 'Share created successfully!' }}"
        csrf-token="{{ csrf_token() }}"></share-document-form>
@endsection
