@extends('layouts.app', ['title' => __('app.sales_returns.title')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.sales_returns.title')]]" />

    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-2xl font-bold">{{ __('app.sales_returns.title') }}</h2>
        <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            {{ __('app.common.view') }} {{ __('app.sales.title') }}
        </a>
    </div>

    <livewire:search.sales-return-search />
@endsection
