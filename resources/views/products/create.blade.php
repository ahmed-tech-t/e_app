@extends('layouts.app', ['title' => 'Create Product'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Products', 'url' => route('products.index')], ['label' => 'Create']]" />

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input name="name_ar" label="Name (Arabic)" :required="true" />
                <x-ui.input name="name_en" label="Name (English)" />
                <x-ui.input name="brand" label="Brand" :required="true" />
                <x-ui.input name="original_code" label="Original Code" />
                <x-ui.select name="category_id" label="Category" :required="true" :options="$categories" valueKey="id" labelKey="name_ar" />
                <x-ui.select name="sale_unit_id" label="Sale Unit" :required="true" :options="$saleUnits" valueKey="id" labelKey="name_ar" />
                <x-ui.input name="units_per_carton" label="Units per Carton" type="number" :min="1" />
                <x-ui.input name="origin" label="Origin" />
                <x-ui.input name="retail_price" label="Retail Price" type="number" :step="'0.01'" :required="true" />
                <x-ui.input name="wholesale_price" label="Wholesale Price" type="number" :step="'0.01'" :required="true" />
                <x-ui.input name="image" label="Product Image" type="file" />
            </div>
            <div class="mt-4">
                <x-ui.input name="description" label="Description" />
            </div>
            <x-form.form-actions :cancelUrl="route('products.index')" />
        </form>
    </div>
@endsection
