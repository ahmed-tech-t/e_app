@extends('layouts.app', ['title' => __('app.sales.details')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.sales.title'), 'url' => route('sales.index')], ['label' => $sale->code]]" />

    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center gap-3">
            <x-ui.back-button :url="route('sales.index')" />
            <a href="{{ route('sales-returns.create', $sale->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-orange-500 hover:bg-orange-600 transition-colors">
                {{ __('app.common.return_items') }}
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">{{ __('app.sales.title') }} {{ $sale->code }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.customer') }}</p>
                    <p class="font-medium">{{ $sale->customer_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.phone') }}</p>
                    <p class="font-medium">{{ $sale->customer_phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.date') }}</p>
                    <p class="font-medium">{{ $sale->created_at?->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.grand_total') }}</p>
                    <p class="font-bold text-lg">{{ number_format($sale->grand_total, 2) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.subtotal') }}</p>
                    <p class="font-medium">{{ number_format($sale->total, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.discount') }}</p>
                    <p class="font-medium">{{ $sale->discount }}%</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.tax') }}</p>
                    <p class="font-medium">{{ $sale->tax }}%</p>
                </div>
            </div>
        </div>

        @if(!empty($sale->items))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold mb-4">{{ __('app.common.items') }}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.product_code') }}</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.quantity') }}</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.price') }}</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($sale->items as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm">
                                    @if($item->product_id)
                                        <a href="{{ route('products.show', $item->product_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">{{ $item->product?->code ?? $item->product_code ?? $item->product_id }}</a>
                                    @else
                                        {{ $item->product?->code ?? $item->product_code ?? $item->product_id }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-sm">{{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-3 text-sm">{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
@endsection
