@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Projects', 'href' => route('admin.projects.index')],
        ['label' => $project['reference']],
    ]" class="mb-6" />

    <x-ui.section-heading
        title="Edit Project"
        :description="$project['name'].' · '.$project['status_label']"
        class="mb-8"
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @include('admin.projects._form', [
        'action' => route('admin.projects.update', $project['reference']),
        'method' => 'PUT',
        'project' => $project,
        'options' => $options,
    ])

    @include('admin.projects._tasks', [
        'project' => $project,
        'options' => $options,
    ])
@endsection
