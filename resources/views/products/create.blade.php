@extends('layouts.app', ['title' => __('app.products.create')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.products.title'), 'url' => route('products.index')], ['label' => __('app.common.create')]]" />

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input name="name_ar" :label="__('app.common.name_ar')" :required="true" />
                <x-ui.input name="name_en" :label="__('app.common.name_en')" />
                <x-ui.input name="brand" :label="__('app.products.brand')" :required="true" />
                <x-ui.input name="original_code" :label="__('app.products.barcode')" />
                <x-ui.select name="category_id" :label="__('app.products.category')" :required="true" :options="$categories" valueKey="id" labelKey="name_ar" />
                <x-ui.select name="sale_unit_id" :label="__('app.products.sale_unit')" :required="true" :options="$saleUnits" valueKey="id" labelKey="name_ar" />
                <x-ui.input name="units_per_carton" :label="__('app.products.units_per_carton')" type="number" :min="1" />
                <x-ui.input name="origin" :label="__('app.products.origin')" />
                <x-ui.input name="size" :label="__('app.products.size')" placeholder="e.g. 130 ml" />
                <x-ui.input name="retail_price" :label="__('app.products.retail_price')" type="number" :step="'0.01'" :required="true" />
                <x-ui.input name="wholesale_price" :label="__('app.products.wholesale_price')" type="number" :step="'0.01'" :required="true" />
                <x-ui.input name="image" :label="__('app.products.image')" type="file" />
            </div>
            <div class="mt-4">
                <x-ui.input name="description" :label="__('app.products.description')" />
            </div>
            <x-form.form-actions :cancelUrl="route('products.index')" />
        </form>
    </div>
@endsection
