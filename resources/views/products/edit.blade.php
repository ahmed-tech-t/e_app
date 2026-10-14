@extends('layouts.app', ['title' => __('app.products.edit')])

@section('content')
    <x-layout.breadcrumb :items="[
        ['label' => __('app.products.title'), 'url' => route('products.index')],
        ['label' => __('app.common.edit')],
    ]" />

    <div
        class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-6 text-center">

                <div id="imagePreviewContainer" onclick="document.getElementById('imageInput').click()"
                    class="w-full max-w-lg aspect-[3/1.5] mx-auto relative rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-400 bg-gray-50 dark:bg-gray-800/60 flex items-center justify-center cursor-pointer overflow-hidden transition-colors group shadow-xs"
                    style="aspect-ratio: 3 / 1.5;" title="{{ __('app.products.click_to_change') }}">
                    @if ($product->image)
                        <x-ui.image id="productImagePreview" :path="$product->image" :alt="$product->name_ar"
                            class="w-full h-full !h-full object-cover group-hover:opacity-85 transition-opacity" />
                    @else
                        <div id="productImagePlaceholder"
                            class="flex flex-col items-center justify-center p-3 text-gray-400 group-hover:text-blue-500 transition-colors">
                            <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-1 text-xs font-medium">{{ __('app.products.click_to_upload') }}</p>
                        </div>
                    @endif
                </div>
                <button type="button" onclick="document.getElementById('imageInput').click()"
                    class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 mt-2 font-medium cursor-pointer transition-colors focus:outline-none underline decoration-dashed underline-offset-2">
                    {{ __('app.products.click_to_change') }}
                </button>
            </div>

            <input type="file" name="image" id="imageInput" class="hidden" accept="image/*"
                onchange="handleProductImageChange(this)" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input name="name_ar" :label="__('app.common.name_ar')" :value="$product->name_ar" />
                <x-ui.input name="name_en" :label="__('app.common.name_en')" :value="$product->name_en" />
                <x-ui.input name="brand" :label="__('app.products.brand')" :value="$product->brand" />
                <x-ui.input name="original_code" :label="__('app.products.barcode')" :value="$product->original_code" />
                <x-ui.select name="category_id" :label="__('app.products.category')" :options="$categories" :selected="$product->category_id" valueKey="id"
                    labelKey="name_ar" />
                <x-ui.select name="sale_unit_id" :label="__('app.products.sale_unit')" :options="$saleUnits" :selected="$product->sale_unit_id" valueKey="id"
                    labelKey="name_ar" />
                <x-ui.input name="units_per_carton" :label="__('app.products.units_per_carton')" type="number" :min="1"
                    :value="$product->units_per_carton" />
                <x-ui.input name="origin" :label="__('app.products.origin')" :value="$product->origin" />
                <x-ui.input name="size" :label="__('app.products.size')" placeholder="e.g. 130 ml" :value="$product->size" />

            </div>
            <div class="mt-4">
                <x-ui.input name="description" :label="__('app.products.description')" :value="$product->description" />
            </div>
            <x-form.form-actions :cancelUrl="route('products.index')" :submitLabel="__('app.common.save')" />
        </form>
    </div>

    <script>
        function handleProductImageChange(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.getElementById('productImagePreview');
                    const placeholder = document.getElementById('productImagePlaceholder');
                    const container = document.getElementById('imagePreviewContainer');

                    if (!preview && container) {
                        preview = document.createElement('img');
                        preview.id = 'productImagePreview';
                        preview.className =
                            'w-full h-full !h-full object-cover group-hover:opacity-85 transition-opacity';
                        preview.alt = '{{ $product->name_ar }}';
                        container.prepend(preview);
                    }

                    if (preview) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
