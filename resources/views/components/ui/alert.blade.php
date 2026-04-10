@props([
    'type' => 'success',
    'message' => '',
])

@php
$typeClasses = [
    'success' => 'bg-green-50 text-green-800 border-green-200 dark:bg-green-900 dark:text-green-200 dark:border-green-800',
    'error' => 'bg-red-50 text-red-800 border-red-200 dark:bg-red-900 dark:text-red-200 dark:border-red-800',
    'warning' => 'bg-yellow-50 text-yellow-800 border-yellow-200 dark:bg-yellow-900 dark:text-yellow-200 dark:border-yellow-800',
    'info' => 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-800',
];
@endphp

<div x-data="{ show: true }" x-init="setTimeout(() => { show = false; }, 5000)" x-show="show" x-transition
     class="mb-4 p-4 rounded-lg border {{ $typeClasses[$type] ?? $typeClasses['success'] }} flex items-center justify-between">
    <span>{{ $message }}</span>
    <button @click="show = false" class="ml-4 hover:opacity-70">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
