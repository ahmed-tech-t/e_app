@props([
    'stockMovements',
    'locations',
    'locationId',
    'type',
    'dateFrom',
    'search' => '',
    'getTypeBadge' 
])
 <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
           
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3 ">
            
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('app.common.search') }}</label>
                <x-ui.search model="search" :placeholder="__('app.stock.search_by_bill_or_name')" />
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('app.locations.title') }}</label>
                <select wire:model.live="locationId" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
                        <option value="">{{ __('app.stock.all_locations') }}</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('app.stock.movement_type') }}</label>
                <select wire:model.live="type" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
                    <option value="">{{ __('app.stock.all_types') }}</option>
                    @foreach(\App\Infrastructure\Persistence\utils\StockMovementType::cases() as $t)
                        <option value="{{ $t->value }}">{{ ucfirst(str_replace('_', ' ', $t->value)) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('app.stock.from_date') }}</label>
                <input type="date" wire:model.live="dateFrom" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
            </div>
            <div class="flex items-center">
                <button wire:click="resetFilters" class="w-full px-4 py-2 text-sm font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">{{ __('app.common.reset') }}</button>
            </div>
        </div>
    </div>

    @if(count($stockMovements?? []) > 0)
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.date') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">{{ __('app.products.title') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden lg:table-cell">{{ __('app.locations.title') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.common.quantity') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('app.stock.movement_type') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden lg:table-cell">{{ __('app.stock.bill_code') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($stockMovements as $movement)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $movement->created_at?->format('M d, Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm hidden md:table-cell">{{ $movement->batch->product->name_ar ?? $movement->batch->product->name_en ?? $movement->product_batch_id }}</td>
                            <td class="px-4 py-3 text-sm hidden lg:table-cell">{{ $movement->location->name ?? $movement->location_id }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $movement->quantity }}</td>
                            <td class="px-4 py-3 text-sm hidden lg:table-cell">
                                <x-ui.badge :variant="$getTypeBadge($movement->type->name)"
                                 :text="ucfirst(str_replace('_', ' ', 
                                 $movement->type->name))" />
                            </td>
                            <td class="px-4 py-3 text-sm hidden lg:table-cell">{{ $movement->bill_number ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-ui.empty-state :title="__('app.stock.no_movements')" :description="__('app.stock.no_movements_desc')" />
    @endif