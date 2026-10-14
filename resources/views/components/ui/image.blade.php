@props([
    'path' => null,
    'alt' => '',
    'lazy' => true,
    'class' => '',
    'placeholder' => 'images/store.png',
])
@php
    $placeholderUrl = asset($placeholder);
    $finalUrl = $placeholderUrl;

    if ($path) {
        $normalized = str_replace('\\', '/', $path);
        // If it's a full URL (external), use it
        if (str_starts_with($normalized, 'http://') || str_starts_with($normalized, 'https://')) {
            $finalUrl = $normalized;
        }
        // If it's in public images (like images/store.png)
        elseif (str_starts_with(ltrim($normalized, '/'), 'images/')) {
            $finalUrl = asset(ltrim($normalized, '/'));
        }
        // If it's a temp file path, use placeholder
        elseif (str_contains($normalized, '/tmp/') || str_contains($normalized, 'Temp/')) {
            $finalUrl = $placeholderUrl;
        }
        // Otherwise, pull from public storage disk if file exists or provide storage URL
        else {
            $storagePath = ltrim($normalized, '/');
            if (str_starts_with($storagePath, 'storage/')) {
                $storagePath = substr($storagePath, 8);
            }
            $finalUrl = Storage::disk('public')->exists($storagePath)
                ? Storage::disk('public')->url($storagePath)
                : $placeholderUrl;
        }
    }
@endphp

<img 
    src="{{ $finalUrl }}" 
    alt="{{ $alt }}"
    loading="{{ $lazy ? 'lazy' : 'eager' }}"
    onerror="this.onerror=null;this.src='{{ $placeholderUrl }}';"
    {{ $attributes->merge(['class' => 'max-w-full h-auto ' . $class]) }}
>