@extends('layouts.app', ['title' => 'Create Sales Return'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Sales Returns', 'url' => route('sales-returns.index')], ['label' => 'New Return']]" />

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <x-ui.back-button :url="route('sales.show', $sale->id)" />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Original Sale: {{ $sale->code }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Customer</p>
                    <p class="font-medium">{{ $sale->customer_name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Date</p>
                    <p class="font-medium">{{ $sale->created_at?->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Grand Total</p>
                    <p class="font-bold">{{ number_format($sale->grand_total, 2) }}</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('sales-returns.store') }}">
            @csrf
            <input type="hidden" name="sales_id" value="{{ $sale->id }}">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Store / Location</label>
                    <select name="store_id" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select location...</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                    @error('store_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Select Items to Return</h3>

                @if(empty($sale->items))
                    <p class="text-sm text-gray-500 dark:text-gray-400">No items found for this sale.</p>
                @else
                    @error('items')<p class="mb-3 text-xs text-red-600">{{ $message }}</p>@enderror

                    <div class="space-y-4">
                        @foreach($sale->items as $index => $item)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Product ID</p>
                                        <p class="font-medium text-sm">{{ $item->product_id }}</p>
                                        <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Original Qty</p>
                                        <p class="font-medium text-sm">{{ $item->quantity }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Return Qty <span class="text-red-500">*</span></label>
                                        <input type="number" name="items[{{ $index }}][quantity]" min="1" max="{{ $item->quantity }}" required
                                            class="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 text-sm focus:ring-1 focus:ring-blue-500">
                                        @error("items.{$index}.quantity")<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Status <span class="text-red-500">*</span></label>
                                        <select name="items[{{ $index }}][restock_status]" required
                                            class="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 text-sm focus:ring-1 focus:ring-blue-500">
                                            <option value="">Select...</option>
                                            <option value="resealable">Resealable</option>
                                            <option value="damaged">Damaged</option>
                                        </select>
                                        @error("items.{$index}.restock_status")<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Reason</label>
                                    <input type="text" name="items[{{ $index }}][reason]" placeholder="Optional reason..."
                                        class="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 text-sm focus:ring-1 focus:ring-blue-500">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if(!empty($sale->items))
                <x-form.form-actions :cancelUrl="route('sales.show', $sale->id)" submitLabel="Submit Return" />
            @endif
        </form>
    </div>
@endsection
