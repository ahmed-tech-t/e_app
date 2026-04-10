@extends('layouts.app', ['title' => 'Stock Transfer'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Stock', 'url' => route('stock.index')], ['label' => 'Transfer']]" />

    <div class="mb-6">
        <x-ui.back-button :url="route('stock.index')" />
    </div>

    <livewire:stock.stock-transfer :products="$products" :locations="$locations" />
@endsection
