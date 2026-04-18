@extends('layouts.app', ['title' => 'Product Details'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Products', 'url' => route('products.index')], ['label' => $product->name_ar]]" />

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('products.edit', $product->id) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <x-ui.back-button :url="route('products.index')" />
        </div>



        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="mb-6 flex justify-center">
                <x-ui.expandable-image :path="$product->image" :alt="$product->name_ar"
                    imageClass="rounded-lg max-h-64 object-contain" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Code</p>
                    <p class="font-medium">{{ $product->code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Name (Arabic)</p>
                    <p class="font-medium">{{ $product->name_ar }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Name (English)</p>
                    <p class="font-medium">{{ $product->name_en ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Brand</p>
                    <p class="font-medium">{{ $product->brand }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Original Code</p>
                    <p class="font-medium">{{ $product->original_code ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Origin</p>
                    <p class="font-medium">{{ $product->origin ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Category</p>
                    <p class="font-medium">{{ $product->category->name_ar ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sale Unit</p>
                    <p class="font-medium">{{ $product->sale_unit->name_ar ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Retail Price</p>
                    <p class="font-medium">{{ number_format($product->retail_price, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Wholesale Price</p>
                    <p class="font-medium">{{ number_format($product->wholesale_price, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Units per Carton</p>
                    <p class="font-medium">{{ $product->units_per_carton ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Description</p>
                    <p class="font-medium">{{ $product->description ?? '-' }}</p>
                </div>
            </div>

        </div>


        <div class="card">
            <div class="card-body">
                <div id="price-chart-container" data-price-history='@json($priceHistory ?? [])'></div>
            </div>
        </div>

        <script src="{{ asset('js/price-chart.js') }}"></script>
@endsection