@extends('layouts.app', ['title' => 'Sales Returns'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Sales Returns']]" />

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Sales Returns</h2>
        <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            View Sales
        </a>
    </div>

    <livewire:search.sales-return-search />
@endsection
