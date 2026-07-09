@extends('layouts.admin')

@section('title', 'Create Lead')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Leads & CRM', 'href' => route('admin.crm.index')],
        ['label' => 'Create'],
    ]" class="mb-6" />

    <x-ui.section-heading
        title="Create CRM Lead"
        description="Add a new enterprise lead to the Cyra-Tech sales pipeline."
        class="cyra-section-heading"
    />

    @include('admin.crm._form', [
        'action' => route('admin.crm.store'),
        'method' => 'POST',
        'lead' => null,
        'options' => $options,
    ])
@endsection
