@props(['product'])
<div>
    <tr>
        <td class="px-4 py-3 text-sm">{{ $product->code }}</td>
        <td class="px-4 py-3 text-sm">{{ $product->name_ar }}</td>
        <td class="px-4 py-3 text-sm hidden md:table-cell">{{ $product->brand }}</td>
        <td class="px-4 py-3 text-sm hidden lg:table-cell">{{ $product->category->name_ar ?? '-' }}</td>
        <td class="px-4 py-3 text-sm hidden lg:table-cell">{{ number_format($product->retail_price, 2) }}</td>
        <td class="px-4 py-3 text-sm hidden lg:table-cell">{{ number_format($product->wholesale_price, 2) }}</td>
        <td class="px-4 py-3 text-sm">
            <div class="flex items-center gap-2">
                <a href="{{ route('products.show', $product->id) }}"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-green-700 bg-green-100 hover:bg-green-200 dark:text-green-400 dark:bg-green-900 dark:hover:bg-green-800">View</a>
                <a href="{{ route('products.edit', $product->id) }}"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-400 dark:bg-blue-900 dark:hover:bg-blue-800">Edit</a>
                <x-ui.delete-button :action="route('products.destroy', $product->id)" />
            </div>
        </td>
    </tr>
</div>