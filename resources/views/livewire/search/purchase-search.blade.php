<?php
use function Livewire\Volt\computed;
use function Livewire\Volt\state;

state(['search' => '', 'perPage' => 10]);

$items = computed(function () {
    $service = app(\App\Application\Services\PurchaseService::class);
    $all = collect($service->findAll());

    if ($this->search) {
        $search = strtolower($this->search);
        $all = $all->filter(function ($item) use ($search) {
            return str_contains(strtolower($item->code ?? ''), $search)
                || str_contains(strtolower((string) $item->supplier_id), $search);
        });
    }

    return $all->values();
});

?>

<div>
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('app.purchases.search_placeholder') }}" class="w-full md:w-1/3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    @if($this->items->count() > 0)
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.code') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">{{ __('app.common.supplier') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">{{ __('app.common.total') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.total') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden lg:table-cell">{{ __('app.common.date') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($this->items as $purchase)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $purchase->code }}</td>
                            <td class="px-4 py-3 text-sm hidden md:table-cell">
                                @if($purchase->supplier_id)
                                    <a href="{{ route('suppliers.show', $purchase->supplier_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $purchase->supplier?->code ?? $purchase->supplier_code ?? $purchase->supplier_id }}</a>
                                @else
                                    {{ $purchase->supplier?->code ?? $purchase->supplier_code ?? '-' }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm hidden md:table-cell">{{ number_format($purchase->total, 2) }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ number_format($purchase->grand_total, 2) }}</td>
                            <td class="px-4 py-3 text-sm hidden lg:table-cell">{{ $purchase->created_at?->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('purchases.show', $purchase->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-400 dark:bg-blue-900 dark:hover:bg-blue-800">{{ __('app.common.view') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-ui.empty-state :title="__('app.purchases.no_found')" description="" />
    @endif
</div>
