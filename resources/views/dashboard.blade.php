@extends('layouts.app', ['title' => __('app.dashboard.title')])

@section('content')
    <x-layout.breadcrumb :items="[]" />

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.dashboard.sales_today') }}</p>
                    <p class="text-2xl font-bold">{{ $salesTodayCount }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($salesTodayTotal, 2) }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.dashboard.purchases_today') }}</p>
                    <p class="text-2xl font-bold">{{ $purchasesTodayCount }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($purchasesTodayTotal, 2) }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.dashboard.total_products') }}</p>
                    <p class="text-2xl font-bold">{{ $totalProducts }}</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.dashboard.sales_returns_today') }}</p>
                    <p class="text-2xl font-bold">{{ $salesReturnsTodayCount }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($salesReturnsTodayTotal, 2) }}</p>
                </div>
                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.dashboard.purchase_returns_today') }}</p>
                    <p class="text-2xl font-bold">{{ $purchaseReturnsTodayCount }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($purchaseReturnsTodayTotal, 2) }}</p>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"/></svg>
                </div>
            </div>
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-ui.card :title="__('app.dashboard.recent_sales')">
            @if(count($recentSales) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.common.code') }}</th>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.common.customer') }}</th>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.common.total') }}</th>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.common.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentSales as $sale)
                                <tr>
                                    <td class="px-3 py-2 text-sm"><a href="{{ route('sales.show', $sale->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $sale->code }}</a></td>
                                    <td class="px-3 py-2 text-sm">{{ $sale->customer_name }}</td>
                                    <td class="px-3 py-2 text-sm">{{ number_format($sale->grand_total, 2) }}</td>
                                    <td class="px-3 py-2 text-sm">{{ $sale->created_at?->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <x-ui.empty-state :title="__('app.dashboard.no_sales')" :description="__('app.dashboard.no_sales_desc')" />
            @endif
        </x-ui.card>

        <x-ui.card :title="__('app.dashboard.recent_purchases')">
            @if(count($recentPurchases) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.common.code') }}</th>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.dashboard.supplier_id') }}</th>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.common.total') }}</th>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.common.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentPurchases as $purchase)
                                <tr>
                                    <td class="px-3 py-2 text-sm"><a href="{{ route('purchases.show', $purchase->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $purchase->code }}</a></td>
                                    <td class="px-3 py-2 text-sm">{{ $purchase->supplier_id }}</td>
                                    <td class="px-3 py-2 text-sm">{{ number_format($purchase->grand_total, 2) }}</td>
                                    <td class="px-3 py-2 text-sm">{{ $purchase->created_at?->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <x-ui.empty-state :title="__('app.dashboard.no_purchases')" :description="__('app.dashboard.no_purchases_desc')" />
            @endif
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-ui.card :title="__('app.dashboard.recent_sales_returns')">
            @if(count($recentSalesReturns) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.common.code') }}</th>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.dashboard.sale_id') }}</th>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.common.total') }}</th>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.common.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentSalesReturns as $r)
                                <tr>
                                    <td class="px-3 py-2 text-sm"><a href="{{ route('sales-returns.show', $r->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $r->code }}</a></td>
                                    <td class="px-3 py-2 text-sm"><a href="{{ route('sales.show', $r->sales_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $r->original_bill_code ?? ('#' . $r->sales_id) }}</a></td>
                                    <td class="px-3 py-2 text-sm">{{ number_format($r->grand_total, 2) }}</td>
                                    <td class="px-3 py-2 text-sm">{{ $r->created_at?->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <x-ui.empty-state :title="__('app.dashboard.no_sales_returns')" :description="__('app.dashboard.no_sales_returns_desc')" />
            @endif
        </x-ui.card>

        <x-ui.card :title="__('app.dashboard.recent_purchase_returns')">
            @if(count($recentPurchaseReturns) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.common.code') }}</th>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.dashboard.purchase_id') }}</th>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.common.total') }}</th>
                                <th class="px-3 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('app.common.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentPurchaseReturns as $r)
                                <tr>
                                    <td class="px-3 py-2 text-sm"><a href="{{ route('purchase-returns.show', $r->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $r->code }}</a></td>
                                    <td class="px-3 py-2 text-sm"><a href="{{ route('purchases.show', $r->purchase_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $r->original_bill_code ?? ('#' . $r->purchase_id) }}</a></td>
                                    <td class="px-3 py-2 text-sm">{{ number_format($r->grand_total, 2) }}</td>
                                    <td class="px-3 py-2 text-sm">{{ $r->created_at?->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <x-ui.empty-state :title="__('app.dashboard.no_purchase_returns')" :description="__('app.dashboard.no_purchase_returns_desc')" />
            @endif
        </x-ui.card>
    </div>
@endsection
