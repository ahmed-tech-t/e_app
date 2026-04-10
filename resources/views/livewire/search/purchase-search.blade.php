<?php
use function Livewire\Volt\computed;
use function Livewire\Volt\state;

state(['search' => '', 'perPage' => 10]);

$items = computed(function () {
    $service = app(\App\Application\Services\PurchaseService::class);
    $all = $service->findAll();

    if ($this->search) {
        $search = strtolower($this->search);
        $all = array_filter($all, function ($item) use ($search) {
            return str_contains(strtolower($item->code ?? ''), $search)
                || str_contains(strtolower((string) $item->supplier_id), $search);
        });
    }

    return collect($all)->values();
});

?>

<div>
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search purchases..." class="w-full md:w-1/3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    @if($this->items->count() > 0)
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">Supplier</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Grand Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden lg:table-cell">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($this->items as $purchase)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $purchase->code }}</td>
                            <td class="px-4 py-3 text-sm hidden md:table-cell">{{ $purchase->supplier_id }}</td>
                            <td class="px-4 py-3 text-sm hidden md:table-cell">{{ number_format($purchase->total, 2) }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ number_format($purchase->grand_total, 2) }}</td>
                            <td class="px-4 py-3 text-sm hidden lg:table-cell">{{ $purchase->created_at?->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('purchases.show', $purchase->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-400 dark:bg-blue-900 dark:hover:bg-blue-800">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-ui.empty-state title="No purchases found" description="Try adjusting your search or create a new purchase." />
    @endif
</div>
