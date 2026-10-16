@extends('layouts.app', ['title' => __('app.suppliers.title')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.suppliers.title'), 'url' => route('suppliers.index')], ['label' => $supplier->name]]" />

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('suppliers.edit', $supplier->id) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                {{ __('app.common.edit') }}
            </a>
            <x-ui.back-button :url="route('suppliers.index')" />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.code') }}</p>
                    <p class="font-medium">{{ $supplier->code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.name') }}</p>
                    <p class="font-medium">{{ $supplier->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.suppliers.email') }}</p>
                    <p class="font-medium">{{ $supplier->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.phone') }}</p>
                    <p class="font-medium">{{ $supplier->phone ?? '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.address') }}</p>
                    <p class="font-medium">{{ $supplier->address ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

