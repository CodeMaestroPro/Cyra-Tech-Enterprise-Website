@extends('layouts.admin')

@section('title', 'Add Client Account')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Client Portal', 'href' => route('admin.client-portal.index')],
        ['label' => 'Add Account'],
    ]" class="mb-6" />

    <x-ui.section-heading class="mb-8" eyebrow="Client Portal" title="Add Client Account" />

    @include('admin.client-portal._form', [
        'action' => route('admin.client-portal.store'),
        'method' => 'POST',
        'account' => null,
    ])
@endsection
