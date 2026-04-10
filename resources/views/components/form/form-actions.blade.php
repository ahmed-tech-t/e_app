@props(['cancelUrl' => '/', 'submitLabel' => 'Save'])

<div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
    <x-ui.button type="submit" variant="primary">{{ $submitLabel }}</x-ui.button>
    <a href="{{ $cancelUrl }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">Cancel</a>
    {{ $extra ?? '' }}
</div>
