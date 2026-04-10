@extends('layouts.app', ['title' => 'Edit Product'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Products', 'url' => route('products.index')], ['label' => 'Edit']]" />

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input name="name_ar" label="Name (Arabic)" :value="$product->name_ar" />
                <x-ui.input name="name_en" label="Name (English)" :value="$product->name_en" />
                <x-ui.input name="brand" label="Brand" :value="$product->brand" />
                <x-ui.input name="original_code" label="Original Code" :value="$product->original_code" />
                <x-ui.select name="category_id" label="Category" :options="$categories" :selected="$product->category_id" valueKey="id" labelKey="name_ar" />
                <x-ui.select name="sale_unit_id" label="Sale Unit" :options="$saleUnits" :selected="$product->sale_unit_id" valueKey="id" labelKey="name_ar" />
                <x-ui.input name="units_per_carton" label="Units per Carton" type="number" :min="1" :value="$product->units_per_carton" />
                <x-ui.input name="origin" label="Origin" :value="$product->origin" />
                <x-ui.input name="image" label="Product Image" type="file" />
            </div>
            <div class="mt-4">
                <x-ui.input name="description" label="Description" :value="$product->description" />
            </div>
            <x-form.form-actions :cancelUrl="route('products.index')" submitLabel="Update" />
        </form>
    </div>
@endsection
