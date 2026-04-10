@extends('layouts.app', ['title' => 'Create Purchase'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Purchases', 'url' => route('purchases.index')], ['label' => 'Create']]" />

    <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6"
         x-data="{
             step: 1,
             supplier_id: '',
             store_id: '',
             discount: 0,
             tax: 0,
             items: [{ product_id: '', quantity: 1, price: 0 }],
             get subtotal() {
                 return this.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
             },
             get grandTotal() {
                 let total = this.subtotal;
                 total = total - (total * this.discount / 100);
                 total = total + (total * this.tax / 100);
                 return total;
             },
             addItem() { this.items.push({ product_id: '', quantity: 1, price: 0 }); },
             removeItem(index) { if (this.items.length > 1) this.items.splice(index, 1); },
         }">

        <div class="flex items-center gap-4 mb-6">
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold" :class="step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500'">1</span>
                <span class="text-sm font-medium">Header</span>
            </div>
            <div class="flex-1 h-0.5" :class="step >= 2 ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'"></div>
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold" :class="step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500'">2</span>
                <span class="text-sm font-medium">Items</span>
            </div>
            <div class="flex-1 h-0.5" :class="step >= 3 ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'"></div>
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold" :class="step >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500'">3</span>
                <span class="text-sm font-medium">Preview</span>
            </div>
        </div>

        <form method="POST" action="{{ route('purchases.store') }}" id="purchaseForm">
            @csrf
            <input type="hidden" name="supplier_id" x-model="supplier_id">
            <input type="hidden" name="store_id" x-model="store_id">
            <input type="hidden" name="discount" x-model="discount">
            <input type="hidden" name="tax" x-model="tax">
            <input type="hidden" name="paper_total" x-model="grandTotal">
            <template x-for="(item, index) in items" :key="index">
                <div>
                    <input type="hidden" :name="'items[' + index + '][product_id]'" x-model="item.product_id">
                    <input type="hidden" :name="'items[' + index + '][quantity]'" x-model="item.quantity">
                    <input type="hidden" :name="'items[' + index + '][price]'" x-model="item.price">
                </div>
            </template>

            <div x-show="step === 1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supplier <span class="text-red-500">*</span></label>
                        <select x-model="supplier_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Store/Location <span class="text-red-500">*</span></label>
                        <select x-model="store_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
                            <option value="">Select Location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Discount (%)</label>
                        <input type="number" x-model="discount" min="0" max="100" step="0.01" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tax (%)</label>
                        <input type="number" x-model="tax" min="0" max="100" step="0.01" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" @click="if(supplier_id && store_id) step = 2" class="px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">Next</button>
                </div>
            </div>

            <div x-show="step === 2">
                <div class="space-y-3">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-end gap-3 p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Product</label>
                                <select x-model="item.product_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-24">
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Qty</label>
                                <input type="number" x-model="item.quantity" min="1" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
                            </div>
                            <div class="w-32">
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Price</label>
                                <input type="number" x-model="item.price" min="0" step="0.01" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
                            </div>
                            <div class="w-32 text-right">
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Total</label>
                                <span class="text-sm font-medium" x-text="(item.quantity * item.price).toFixed(2)"></span>
                            </div>
                            <button type="button" @click="removeItem(index)" class="p-2 text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </template>
                </div>
                <button type="button" @click="addItem()" class="mt-3 inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:bg-blue-900 dark:hover:bg-blue-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Item
                </button>
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <span class="text-lg font-bold">Subtotal: <span x-text="subtotal.toFixed(2)"></span></span>
                    <div class="flex gap-2">
                        <button type="button" @click="step = 1" class="px-4 py-2 text-sm font-medium rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600">Back</button>
                        <button type="button" @click="if(items.some(i => !i.product_id)) return; step = 3" class="px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">Preview</button>
                    </div>
                </div>
            </div>

            <div x-show="step === 3">
                <h3 class="text-lg font-semibold mb-4">Purchase Summary</h3>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 mb-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div><span class="text-gray-500 dark:text-gray-400">Subtotal:</span> <span x-text="subtotal.toFixed(2)"></span></div>
                        <div><span class="text-gray-500 dark:text-gray-400">Discount:</span> <span x-text="discount + '%'"></span></div>
                        <div><span class="text-gray-500 dark:text-gray-400">Tax:</span> <span x-text="tax + '%'"></span></div>
                        <div><span class="text-gray-500 dark:text-gray-400 font-bold">Grand Total:</span> <span class="font-bold" x-text="grandTotal.toFixed(2)"></span></div>
                    </div>
                </div>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mb-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Product</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Qty</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Price</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="px-4 py-2 text-sm" x-text="item.product_id"></td>
                                <td class="px-4 py-2 text-sm" x-text="item.quantity"></td>
                                <td class="px-4 py-2 text-sm" x-text="parseFloat(item.price).toFixed(2)"></td>
                                <td class="px-4 py-2 text-sm" x-text="(item.quantity * item.price).toFixed(2)"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="flex justify-between">
                    <button type="button" @click="step = 2" class="px-4 py-2 text-sm font-medium rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600">Back</button>
                    <button type="submit" class="px-6 py-2 text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700">Confirm Purchase</button>
                </div>
            </div>
        </form>
    </div>
@endsection
