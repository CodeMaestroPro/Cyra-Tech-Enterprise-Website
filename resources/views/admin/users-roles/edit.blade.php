@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Users & Roles', 'href' => route('admin.users-roles.index')],
        ['label' => $user->name],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="Identity Management"
        title="Edit User"
        description="Update account details and role assignments for {{ $user->email }}."
        class="cyra-section-heading mb-8"
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @include('admin.users-roles._form', [
        'action' => route('admin.users-roles.update', $user),
        'method' => 'PUT',
        'user' => $user,
        'formOptions' => $formOptions,
    ])
@endsection
