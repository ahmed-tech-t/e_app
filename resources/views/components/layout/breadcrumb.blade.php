@props(['items' => []])

<nav class="mb-4" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
        <li><a href="/" class="hover:text-gray-700 dark:hover:text-gray-200">Home</a></li>
        @foreach($items as $i => $item)
            <li class="flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" class="hover:text-gray-700 dark:hover:text-gray-200">{{ $item['label'] }}</a>
                @else
                    <span class="text-gray-700 dark:text-gray-200 font-medium">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
