@extends('layouts.admin')

@section('title', 'Create Project')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Projects', 'href' => route('admin.projects.index')],
        ['label' => 'Create'],
    ]" class="mb-6" />

    <x-ui.section-heading
        title="Create Project"
        description="Add a new enterprise delivery program to the Cyra-Tech project portfolio."
        class="cyra-section-heading"
    />

    @include('admin.projects._form', [
        'action' => route('admin.projects.store'),
        'method' => 'POST',
        'project' => null,
        'options' => $options,
    ])
@endsection
