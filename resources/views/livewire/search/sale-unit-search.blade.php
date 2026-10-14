<?php
use function Livewire\Volt\computed;
use function Livewire\Volt\state;

state(['search' => '', 'perPage' => 10]);

$items = computed(function () {
    $service = app(\App\Application\Services\SaleUnitService::class);
    $all = collect($service->findAll());

    if ($this->search) {
        $search = strtolower($this->search);
        $all = $all->filter(function ($item) use ($search) {
            return str_contains(strtolower($item->name_ar ?? ''), $search)
                || str_contains(strtolower($item->name_en ?? ''), $search)
                || str_contains(strtolower($item->code ?? ''), $search);
        });
    }

    return $all->values();
});

?>

<div>
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('app.sale_units.search_placeholder') }}" class="w-full md:w-1/3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    @if($this->items->count() > 0)
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.code') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.name_ar') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.name_en') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($this->items as $unit)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $unit->code }}</td>
                            <td class="px-4 py-3 text-sm">{{ $unit->name_ar }}</td>
                            <td class="px-4 py-3 text-sm">{{ $unit->name_en ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('sale-units.edit', $unit->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-400 dark:bg-blue-900 dark:hover:bg-blue-800">{{ __('app.common.edit') }}</a>
                                    <x-ui.delete-button :action="route('sale-units.destroy', $unit->id)" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-ui.empty-state :title="__('app.sale_units.no_found')" :description="__('app.sale_units.no_found_desc')" />
    @endif
</div>
