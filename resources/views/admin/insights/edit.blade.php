@extends('layouts.admin')

@section('title', 'Edit Insight')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Insights', 'href' => route('admin.insights.index')],
        ['label' => $article['title']],
    ]" class="mb-6" />

    <x-ui.section-heading
        title="Edit Insight Article"
        description="Update {{ $article['title'] }} for the public Insights hub."
        class="cyra-section-heading mb-8"
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @include('admin.insights._form', [
        'action' => route('admin.insights.update', $article['slug']),
        'method' => 'PUT',
        'article' => $article,
        'options' => $options,
    ])
@endsection
