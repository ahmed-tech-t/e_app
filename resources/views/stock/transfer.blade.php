@extends('layouts.app', ['title' => __('app.stock.transfer')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.nav.movements'), 'url' => route('stock.index')], ['label' => __('app.stock.transfer')]]" />

    <div class="mb-6">
        <x-ui.back-button :url="route('stock.index')" />
    </div>

    <livewire:stock.stock-transfer :products="$products" :locations="$locations" />
@endsection
