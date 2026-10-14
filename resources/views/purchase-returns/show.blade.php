@extends('layouts.app', ['title' => 'Purchase Return Details'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Purchase Returns', 'url' => route('purchase-returns.index')], ['label' => $return->code]]" />

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <x-ui.back-button :url="route('purchase-returns.index')" />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Return {{ $return->code }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Original Purchase</p>
                    <p class="font-medium">
                        <a href="{{ route('purchases.show', $originalPurchase->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $originalPurchase->code }}</a>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Supplier ID</p>
                    <p class="font-medium">{{ $originalPurchase->supplier_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Date</p>
                    <p class="font-medium">{{ $return->created_at?->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Grand Total</p>
                    <p class="font-bold text-lg">{{ number_format($return->grand_total, 2) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Subtotal</p>
                    <p class="font-medium">{{ number_format($return->total, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Discount</p>
                    <p class="font-medium">{{ $return->discount }}%</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tax</p>
                    <p class="font-medium">{{ $return->tax }}%</p>
                </div>
            </div>
        </div>

        @if(!empty($return->items))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold mb-4">Returned Items</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Product ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Reason</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($return->items as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm">{{ $item->product_id }}</td>
                                <td class="px-4 py-3 text-sm">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-sm">{{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-3 text-sm">{{ number_format($item->total, 2) }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $item->restock_status?->value === 'resealable' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ ucfirst($item->restock_status?->value ?? '-') }}
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
