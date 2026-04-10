@props([
    'name' => '',
    'label' => '',
    'options' => [],
    'valueKey' => 'id',
    'labelKey' => 'name',
    'selected' => null,
    'error' => null,
    'required' => false,
    'placeholder' => 'Select...',
])

@php
$error = $error ?? ($errors ?? null) ? ($errors->has($name) ? $errors->first($name) : null) : null;
@endphp

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
        class="w-full rounded-lg border {{ $error ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400"
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $option)
            @if(is_object($option))
                <option value="{{ $option->{$valueKey} }}" {{ (string) old($name, $selected) === (string) $option->{$valueKey} ? 'selected' : '' }}>{{ $option->{$labelKey} }}</option>
            @else
                <option value="{{ $option[$valueKey] ?? $loop->index }}" {{ (string) old($name, $selected) === (string) ($option[$valueKey] ?? $loop->index) ? 'selected' : '' }}>{{ $option[$labelKey] ?? $option }}</option>
            @endif
        @endforeach
    </select>
    @if($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
