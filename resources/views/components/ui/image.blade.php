@props([
    'path' => null,
    'alt' => '',
    'lazy' => true,
    'class' => '',
    'placeholder' => 'images/store.png',
])
@php
    $finalUrl = asset($placeholder);

    if ($path) {
        // If it's a full URL (external), use it
        if (str_starts_with($path, 'http')) {
            $finalUrl = $path;
        }
        // If it's a temp file path (like /tmp/...), use the placeholder
        elseif (str_starts_with($path, '/tmp/')) {
            $finalUrl = asset($placeholder);
        }
        // Otherwise, pull it from the 'public' disk storage
        else {
            $finalUrl = Storage::disk('public')->url($path);
        }
    }
@endphp

                  
   <img 
    src="{{ $finalUrl }}" 
    alt="{{ $alt }}"
    loading="{{ $lazy ? 'lazy' : 'eager' }}"
    {{ $attributes->merge(['class' => 'max-w-full h-auto ' . $class]) }}
>