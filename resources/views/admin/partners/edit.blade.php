@extends('layouts.admin')

@section('title', 'Edit Partner')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Partners', 'href' => route('admin.partners.index')],
        ['label' => $program['title']],
    ]" class="mb-6" />

    <x-ui.section-heading
        title="Edit Partner"
        description="Update {{ $program['title'] }} program details and visibility."
        class="cyra-section-heading mb-8"
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @include('admin.partners._form', [
        'action' => route('admin.partners.update', $program['slug']),
        'method' => 'PUT',
        'program' => $program,
        'options' => $options,
    ])
@endsection
