<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;

state(['search' => '']);

$items = computed(function () {
    $service = app(\App\Application\Services\PurchaseReturnService::class);
    $all = collect($service->findAll());

    if ($this->search) {
        $search = strtolower($this->search);
        $all = $all->filter(function ($item) use ($search) {
            return str_contains(strtolower($item->code ?? ''), $search);
        });
    }

    return $all->values();
});
?>

<div>
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search purchase returns..."
            class="w-full md:w-1/3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    @if($this->items->count() > 0)
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">Original Purchase ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Grand Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden lg:table-cell">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($this->items as $item)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $item->code }}</td>
                            <td class="px-4 py-3 text-sm hidden md:table-cell">
                                <a href="{{ route('purchases.show', $item->purchase_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">#{{ $item->purchase_id }}</a>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium">{{ number_format($item->grand_total, 2) }}</td>
                            <td class="px-4 py-3 text-sm hidden lg:table-cell">{{ $item->created_at?->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('purchase-returns.show', $item->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-400 dark:bg-blue-900 dark:hover:bg-blue-800">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-ui.empty-state title="No purchase returns found" description="Purchase returns will appear here once created." />
    @endif
</div>
