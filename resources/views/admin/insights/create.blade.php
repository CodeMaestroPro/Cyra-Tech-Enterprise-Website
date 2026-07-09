@extends('layouts.admin')

@section('title', 'Create Insight')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Insights', 'href' => route('admin.insights.index')],
        ['label' => 'Create'],
    ]" class="mb-6" />

    <x-ui.section-heading
        title="Create Insight Article"
        description="Add a new thought leadership article to the public Insights hub."
        class="cyra-section-heading mb-8"
    />

    @include('admin.insights._form', [
        'action' => route('admin.insights.store'),
        'method' => 'POST',
        'article' => null,
        'options' => $options,
    ])
@endsection
