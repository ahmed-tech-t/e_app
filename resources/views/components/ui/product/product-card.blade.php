@props(['product'])

<tr 
    @click="selectedId = (selectedId === {{ $product->id }} ? null : {{ $product->id }})"
    :class="selectedId === {{ $product->id }} 
        ? 'bg-blue-50/90 dark:bg-blue-950/40 ring-1 ring-inset ring-blue-500/40 text-blue-950 dark:text-blue-100' 
        : 'hover:bg-gray-50/80 dark:hover:bg-gray-800/60'"
    class="cursor-pointer transition-colors"
>
    <td class="px-4 py-3 text-sm">
        <div class="w-12 h-12 aspect-square rounded-lg overflow-hidden shrink-0 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-center transition-all"
             :class="selectedId === {{ $product->id }} ? 'ring-2 ring-blue-500 dark:ring-blue-400' : ''">
            <x-ui.expandable-image 
                :path="$product->image" 
                :alt="$product->name_ar" 
                imageClass="w-full h-full !w-full !h-full aspect-square !aspect-square object-cover !object-cover" 
            />
        </div>
    </td>
    <td class="px-4 py-3 text-sm font-medium">{{ $product->code }}</td>
    <td class="px-4 py-3 text-sm font-medium">{{ $product->name_ar }}</td>
    <td class="px-4 py-3 text-sm hidden md:table-cell text-gray-600 dark:text-gray-300">{{ $product->brand }}</td>
    <td class="px-4 py-3 text-sm hidden lg:table-cell text-gray-600 dark:text-gray-300">{{ $product->category->name_ar ?? '-' }}</td>
    <td class="px-4 py-3 text-sm hidden lg:table-cell text-gray-700 dark:text-gray-200 font-mono">{{ number_format($product->retail_price, 2) }}</td>
    <td class="px-4 py-3 text-sm hidden lg:table-cell text-gray-700 dark:text-gray-200 font-mono">{{ number_format($product->wholesale_price, 2) }}</td>
    <td class="px-4 py-3 text-sm" @click.stop>
        <div class="flex items-center gap-2">
            <a href="{{ route('products.show', $product->id) }}"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-green-700 bg-green-100 hover:bg-green-200 dark:text-green-400 dark:bg-green-900 dark:hover:bg-green-800 transition-colors">{{ __('app.common.view') }}</a>
            <a href="{{ route('products.edit', $product->id) }}"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-400 dark:bg-blue-900 dark:hover:bg-blue-800 transition-colors">{{ __('app.common.edit') }}</a>
            <x-ui.delete-button :action="route('products.destroy', $product->id)" />
        </div>
    </td>
</tr>