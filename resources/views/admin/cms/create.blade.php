@extends('layouts.admin')

@section('title', 'Create Page')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Pages', 'href' => route('admin.cms.index')],
        ['label' => 'Create'],
    ]" class="mb-6" />

    <x-ui.section-heading
        title="Create CMS Page"
        description="Add a new legal, policy, or editorial page to the Cyra-Tech content library."
        class="cyra-section-heading"
    />

    @include('admin.cms._form', [
        'action' => route('admin.cms.store'),
        'method' => 'POST',
        'page' => null,
        'templates' => $templates,
        'statuses' => $statuses,
    ])
@endsection
