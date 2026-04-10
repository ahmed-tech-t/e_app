@extends('layouts.app', ['title' => 'Purchase Details'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Purchases', 'url' => route('purchases.index')], ['label' => $purchase->code]]" />

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <x-ui.back-button :url="route('purchases.index')" />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Purchase {{ $purchase->code }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Supplier ID</p>
                    <p class="font-medium">{{ $purchase->supplier_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Store ID</p>
                    <p class="font-medium">{{ $purchase->store_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Date</p>
                    <p class="font-medium">{{ $purchase->created_at?->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Grand Total</p>
                    <p class="font-bold text-lg">{{ number_format($purchase->grand_total, 2) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Subtotal</p>
                    <p class="font-medium">{{ number_format($purchase->total, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Discount</p>
                    <p class="font-medium">{{ $purchase->discount }}%</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tax</p>
                    <p class="font-medium">{{ $purchase->tax }}%</p>
                </div>
            </div>
        </div>

        @if(!empty($purchase->items))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold mb-4">Items</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Product ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($purchase->items as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm">{{ $item->product_id }}</td>
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
