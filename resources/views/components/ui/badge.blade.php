@props([
    'variant' => 'default',
    'text' => '',
])

@php
$variantClasses = [
    'success' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    'danger' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    'default' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $variantClasses[$variant] ?? $variantClasses['default'] }}">
    {{ $text }}
</span>
