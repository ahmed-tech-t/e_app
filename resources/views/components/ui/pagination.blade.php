@props(['paginator' => null])

@if($paginator && $paginator->hasPages())
    <div class="flex items-center justify-between mt-4">
        <p class="text-sm text-gray-700 dark:text-gray-300">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </p>
        <div class="flex gap-1">
            @if($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-lg cursor-not-allowed">&laquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600">&laquo;</a>
            @endif

            @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if($page == $paginator->currentPage())
                    <span class="px-3 py-2 text-sm text-white bg-blue-600 rounded-lg">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 hidden sm:inline-block">{{ $page }}</a>
                @endif
            @endforeach

            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600">&raquo;</a>
            @else
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-lg cursor-not-allowed">&raquo;</span>
            @endif
        </div>
    </div>
@endif
