@extends('layouts.app', ['title' => __('app.purchase_returns.details')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.purchase_returns.title'), 'url' => route('purchase-returns.index')], ['label' => $return->code]]" />

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <x-ui.back-button :url="route('purchase-returns.index')" />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">{{ __('app.purchase_returns.title') }} {{ $return->code }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.original_purchase') }}</p>
                    <p class="font-medium">
                        <a href="{{ route('purchases.show', $originalPurchase->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $originalPurchase->code }}</a>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.purchases.supplier') }} {{ __('app.common.code') }}</p>
                    <p class="font-medium">
                        @if($originalPurchase->supplier_id)
                            <a href="{{ route('suppliers.show', $originalPurchase->supplier_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $originalPurchase->supplier?->code ?? $originalPurchase->supplier_code ?? $originalPurchase->supplier_id }}</a>
                        @else
                            {{ $originalPurchase->supplier?->code ?? $originalPurchase->supplier_code ?? '-' }}
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.date') }}</p>
                    <p class="font-medium">{{ $return->created_at?->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.grand_total') }}</p>
                    <p class="font-bold text-lg">{{ number_format($return->grand_total, 2) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.subtotal') }}</p>
                    <p class="font-medium">{{ number_format($return->total, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.discount') }}</p>
                    <p class="font-medium">{{ $return->discount }}%</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.tax') }}</p>
                    <p class="font-medium">{{ $return->tax }}%</p>
                </div>
            </div>
        </div>

        @if(!empty($return->items))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold mb-4">{{ __('app.common.returned_items') }}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.product') }}</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.quantity') }}</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.price') }}</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.total') }}</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.status') }}</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.reason') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($return->items as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm">
                                    @if($item->product_id)
                                        <a href="{{ route('products.show', $item->product_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">{{ $item->product?->code ?? $item->product_code ?? $item->product_id }}</a>
                                    @else
                                        {{ $item->product?->code ?? $item->product_code ?? $item->product_id }}
                                    @endif
                                    - {{ app()->getLocale() === 'ar' ? ($item->product?->name_ar ?? $item->product?->name_en) : ($item->product?->name_en ?? $item->product?->name_ar) }}
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-sm">{{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-3 text-sm">{{ number_format($item->total, 2) }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $item->restock_status?->value === 'resealable' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $item->restock_status?->value === 'resealable' ? __('app.common.resealable') : ($item->restock_status?->value === 'damaged' ? __('app.common.damaged') : '-') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $item->reason ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
@endsection
