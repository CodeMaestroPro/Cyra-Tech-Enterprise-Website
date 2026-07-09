@extends('layouts.admin')

@section('title', 'Add '.$config['singular'])

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => $config['label'], 'href' => route('admin.'.$module.'.index')],
        ['label' => 'Add '.$config['singular']],
    ]" class="mb-6" />

    <x-ui.section-heading
        class="mb-8"
        :eyebrow="$config['label']"
        :title="'Add '.$config['singular']"
        :description="'Create a new '.$config['singular'].' entry.'"
    />

    @include('admin.catalog._form', [
        'action' => route('admin.'.$module.'.store'),
        'method' => 'POST',
        'record' => null,
    ])
@endsection
