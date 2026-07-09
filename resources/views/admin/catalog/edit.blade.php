@extends('layouts.admin')

@section('title', 'Edit '.$config['singular'])

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => $config['label'], 'href' => route('admin.'.$module.'.index')],
        ['label' => $record['display_title']],
    ]" class="mb-6" />

    <x-ui.section-heading
        class="mb-8"
        :eyebrow="$config['label']"
        :title="$record['display_title']"
        :description="'Edit '.$config['singular'].' details and visibility.'"
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @include('admin.catalog._form', [
        'action' => route('admin.'.$module.'.update', $record['slug']),
        'method' => 'PUT',
        'record' => $record,
    ])
@endsection
