@props(['title' => '', 'icon' => ''])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
    @if($title)
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center gap-2">
            @if($icon)
                <span class="text-gray-500 dark:text-gray-400">{{ $icon }}</span>
            @endif
            <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
        </div>
    @endif
    <div class="p-4">
        {{ $slot }}
    </div>
    @if(isset($footer))
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $footer }}
        </div>
    @endif
</div>
