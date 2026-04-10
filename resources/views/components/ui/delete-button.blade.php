@props(['action' => '', 'id' => ''])

<form method="POST" action="{{ $action }}" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit"
            onclick="return confirm('Are you sure you want to delete this item?')"
            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-red-700 bg-red-100 hover:bg-red-200 dark:text-red-400 dark:bg-red-900 dark:hover:bg-red-800 transition-colors">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        Delete
    </button>
</form>
