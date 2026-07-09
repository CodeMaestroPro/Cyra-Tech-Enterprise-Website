@extends('layouts.admin')

@section('title', 'Edit Client Account')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Client Portal', 'href' => route('admin.client-portal.index')],
        ['label' => $account['name']],
    ]" class="mb-6" />

    <x-ui.section-heading class="mb-8" eyebrow="Client Portal" :title="$account['name']" />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @include('admin.client-portal._form', [
        'action' => route('admin.client-portal.update', $account['slug']),
        'method' => 'PUT',
        'account' => $account,
    ])
@endsection
