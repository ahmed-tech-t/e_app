@extends('layouts.app', ['title' => 'Stock Movements'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Stock Movements']]" />

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Stock Movements</h2>
        <a href="{{ route('stock.transfer') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            Transfer Product
        </a>
    </div>

    <livewire:stock.stock-movements />
@endsection
