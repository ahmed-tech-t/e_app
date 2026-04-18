@extends('layouts.app', ['title' => 'Edit Product'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Products', 'url' => route('products.index')], ['label' => 'Edit']]" />

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-6 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Product Image:</p>
                <div class="inline-block relative">
                    @if($product->image)
                        <x-ui.image :path="$product->image" :alt="$product->name_ar" class="rounded-lg max-h-48 object-contain cursor-pointer hover:opacity-75 transition-opacity" onclick="document.getElementById('imageInput').click()" />
                    @else
                        <div class="rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 p-8 cursor-pointer hover:border-gray-400 dark:hover:border-gray-500 transition-colors" onclick="document.getElementById('imageInput').click()">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Click to upload image</p>
                        </div>
                    @endif
                </div>
                <p class="text-xs text-gray-400 mt-2">Click image to change</p>
            </div>
            
            <input type="file" name="image" id="imageInput" class="hidden" accept="image/*" onchange="this.form.submit()" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input name="name_ar" label="Name (Arabic)" :value="$product->name_ar" />
                <x-ui.input name="name_en" label="Name (English)" :value="$product->name_en" />
                <x-ui.input name="brand" label="Brand" :value="$product->brand" />
                <x-ui.input name="original_code" label="Original Code" :value="$product->original_code" />
                <x-ui.select name="category_id" label="Category" :options="$categories" :selected="$product->category_id" valueKey="id" labelKey="name_ar" />
                <x-ui.select name="sale_unit_id" label="Sale Unit" :options="$saleUnits" :selected="$product->sale_unit_id" valueKey="id" labelKey="name_ar" />
                <x-ui.input name="units_per_carton" label="Units per Carton" type="number" :min="1" :value="$product->units_per_carton" />
                <x-ui.input name="origin" label="Origin" :value="$product->origin" />
                
            </div>
            <div class="mt-4">
                <x-ui.input name="description" label="Description" :value="$product->description" />
            </div>
            <x-form.form-actions :cancelUrl="route('products.index')" submitLabel="Update" />
        </form>
    </div>

    
@endsection
