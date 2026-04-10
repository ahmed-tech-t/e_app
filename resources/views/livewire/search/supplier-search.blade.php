<?php
use function Livewire\Volt\computed;
use function Livewire\Volt\state;

state(['search' => '', 'perPage' => 10]);

$items = computed(function () {
    $service = app(\App\Application\Services\SupplierService::class);
    $all = $service->findAll();

    if ($this->search) {
        $search = strtolower($this->search);
        $all = array_filter($all, function ($item) use ($search) {
            return str_contains(strtolower($item->name ?? ''), $search)
                || str_contains(strtolower($item->email ?? ''), $search)
                || str_contains(strtolower($item->phone ?? ''), $search);
        });
    }

    return collect($all)->values();
});

?>

<div>
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search suppliers..." class="w-full md:w-1/3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    @if($this->items->count() > 0)
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden lg:table-cell">Address</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($this->items as $supplier)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $supplier->code }}</td>
                            <td class="px-4 py-3 text-sm">{{ $supplier->name }}</td>
                            <td class="px-4 py-3 text-sm hidden md:table-cell">{{ $supplier->email ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm hidden md:table-cell">{{ $supplier->phone ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm hidden lg:table-cell">{{ $supplier->address ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-400 dark:bg-blue-900 dark:hover:bg-blue-800">Edit</a>
                                    <x-ui.delete-button :action="route('suppliers.destroy', $supplier->id)" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-ui.empty-state title="No suppliers found" description="Try adjusting your search or create a new supplier." />
    @endif
</div>
