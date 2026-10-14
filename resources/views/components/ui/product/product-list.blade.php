@props(['items'])

@if($items->count() > 0)
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Image</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.code') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.name_ar') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">{{ __('app.products.brand') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden lg:table-cell">{{ __('app.products.category') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden lg:table-cell">{{ __('app.products.retail_price') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden lg:table-cell">{{ __('app.products.wholesale_price') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($items as $product)
                    <x-ui.product.product-card :product="$product" />
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <x-ui.empty-state :title="__('app.products.no_found')" :description="__('app.products.no_found_desc')" />
@endif