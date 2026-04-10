@props(['name' => '', 'label' => '', 'error' => null])

@php
$error = $error ?? ($errors ?? null) ? ($errors->has($name) ? $errors->first($name) : null) : null;
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif
    {{ $slot }}
    @if($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
