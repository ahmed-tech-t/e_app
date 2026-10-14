@props(['path', 'alt', 'imageClass' => 'w-12 h-12 aspect-square object-cover rounded-lg'])

<div class="inline-flex w-full h-full shrink-0">
    <x-ui.image 
        :path="$path" 
        :alt="$alt" 
        id="{{ 'expandable-image-' . md5($path . $alt) }}" 
        class="{{ $imageClass }} !object-cover cursor-pointer hover:opacity-75 transition-all duration-300 ease-in-out expandable-image" 
        data-path="{{ $path }}"
        data-alt="{{ $alt }}"
        style="object-fit: cover;"
        {{ $attributes }}
    />
</div>