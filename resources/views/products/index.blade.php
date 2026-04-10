@extends('layouts.app', ['title' => 'Products'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Products']]" />

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Products</h2>
        <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </a>
    </div>

    <livewire:search.product-search />
@endsection
