@extends('layouts.admin')

@section('title', 'Edit Lead')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Leads & CRM', 'href' => route('admin.crm.index')],
        ['label' => $lead['reference']],
    ]" class="mb-6" />

    <x-ui.section-heading
        title="Edit Lead"
        :description="$lead['name'].' · '.$lead['pipeline_stage_label']"
        class="mb-8"
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @include('admin.crm._form', [
        'action' => route('admin.crm.update', $lead['reference']),
        'method' => 'PUT',
        'lead' => $lead,
        'options' => $options,
    ])
@endsection
