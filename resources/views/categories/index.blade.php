@extends('layouts.app', ['title' => __('app.categories.title')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.categories.title')]]" />

    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-2xl font-bold">{{ __('app.categories.title') }}</h2>
        <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('app.categories.add_new') }}
        </a>
    </div>

    <livewire:search.category-search />
@endsection
