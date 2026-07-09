@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Users & Roles', 'href' => route('admin.users-roles.index')],
        ['label' => 'Create User'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="Identity Management"
        title="Create User"
        description="Add a new platform account and assign RBAC roles."
        class="cyra-section-heading mb-8"
    />

    @include('admin.users-roles._form', [
        'action' => route('admin.users-roles.store'),
        'method' => 'POST',
        'user' => null,
        'formOptions' => $formOptions,
    ])
@endsection
