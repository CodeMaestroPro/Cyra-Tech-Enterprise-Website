@extends('layouts.admin')

@section('title', 'Add Partner')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Partners', 'href' => route('admin.partners.index')],
        ['label' => 'Add Partner'],
    ]" class="mb-6" />

    <x-ui.section-heading
        title="Add Partner"
        description="Create a new partner program for the public Partner Hub."
        class="cyra-section-heading mb-8"
    />

    @include('admin.partners._form', [
        'action' => route('admin.partners.store'),
        'method' => 'POST',
        'program' => null,
        'options' => $options,
    ])
@endsection
