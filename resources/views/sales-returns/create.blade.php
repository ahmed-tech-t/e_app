@extends('layouts.app', ['title' => __('app.sales_returns.create')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.sales_returns.title'), 'url' => route('sales-returns.index')], ['label' => __('app.common.new_return')]]" />

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <x-ui.back-button :url="route('sales.show', $sale->id)" />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">{{ __('app.common.original_sale') }}: {{ $sale->code }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">{{ __('app.common.customer') }}</p>
                    <p class="font-medium">{{ $sale->customer_name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">{{ __('app.common.date') }}</p>
                    <p class="font-medium">{{ $sale->created_at?->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">{{ __('app.common.grand_total') }}</p>
                    <p class="font-bold">{{ number_format($sale->grand_total, 2) }}</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('sales-returns.store') }}">
            @csrf
            <input type="hidden" name="sales_id" value="{{ $sale->id }}">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('app.locations.title') }}</label>
                    <select name="store_id" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('app.common.select_location') }}</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                    @error('store_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('app.common.select_items_to_return') }}</h3>

                @if(empty($sale->items))
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.no_items_found') }}</p>
                @else
                    @error('items')<p class="mb-3 text-xs text-red-600">{{ $message }}</p>@enderror

                    <div class="space-y-4">
                        @foreach($sale->items as $index => $item)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('app.common.product') }}</p>
                                        <p class="font-medium text-sm">
                                            @if($item->product_id)
                                                <a href="{{ route('products.show', $item->product_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $item->product?->code ?? $item->product_code ?? $item->product_id }}</a>
                                            @else
                                                {{ $item->product?->code ?? $item->product_code ?? $item->product_id }}
                                            @endif
                                            - {{ app()->getLocale() === 'ar' ? ($item->product?->name_ar ?? $item->product?->name_en) : ($item->product?->name_en ?? $item->product?->name_ar) }}
                                        </p>
                                        <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('app.common.original_qty') }}</p>
                                        <p class="font-medium text-sm">{{ $item->quantity }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('app.common.return_qty') }}</label>
                                        <input type="number" name="items[{{ $index }}][quantity]" min="1" max="{{ $item->quantity }}" placeholder="0"
                                            value="{{ old("items.{$index}.quantity") }}"
                                            class="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 text-sm focus:ring-1 focus:ring-blue-500">
                                        @error("items.{$index}.quantity")<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('app.common.status') }}</label>
                                        <select name="items[{{ $index }}][restock_status]"
                                            class="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 text-sm focus:ring-1 focus:ring-blue-500">
                                            <option value="resealable" {{ old("items.{$index}.restock_status", 'resealable') === 'resealable' ? 'selected' : '' }}>{{ __('app.common.resealable') }}</option>
                                            <option value="damaged" {{ old("items.{$index}.restock_status") === 'damaged' ? 'selected' : '' }}>{{ __('app.common.damaged') }}</option>
                                        </select>
                                        @error("items.{$index}.restock_status")<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('app.common.reason') }}</label>
                                    <input type="text" name="items[{{ $index }}][reason]" placeholder="{{ __('app.common.reason') }}..."
                                        class="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 text-sm focus:ring-1 focus:ring-blue-500">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if(!empty($sale->items))
                <x-form.form-actions :cancelUrl="route('sales.show', $sale->id)" :submitLabel="__('app.common.submit_return')" />
            @endif
        </form>
    </div>
@endsection
