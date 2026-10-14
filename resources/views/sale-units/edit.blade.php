@extends('layouts.app', ['title' => __('app.sale_units.edit')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.sale_units.title'), 'url' => route('sale-units.index')], ['label' => __('app.common.edit')]]" />

    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('sale-units.update', $saleUnit->id) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input name="name_ar" :label="__('app.common.name_ar')" :value="$saleUnit->name_ar" :required="true" />
                <x-ui.input name="name_en" :label="__('app.common.name_en')" :value="$saleUnit->name_en" />
            </div>
            <x-form.form-actions :cancelUrl="route('sale-units.index')" :submitLabel="__('app.common.save')" />
        </form>
    </div>
@endsection
