@props(['path', 'alt', 'imageClass' => 'w-12 h-12 rounded-lg object-cover'])

{{-- Debug: Component is being rendered --}}
<div class="inline-block">
    <x-ui.image 
        :path="$path" 
        :alt="$alt" 
        id="{{ 'expandable-image-' . md5($path . $alt) }}" 
        class="{{ $imageClass }} cursor-pointer hover:opacity-75 transition-all duration-500 ease-in-out expandable-image" 
        data-path="{{ $path }}"
        data-alt="{{ $alt }}"
    />
</div>