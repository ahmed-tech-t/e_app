<div class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 md:px-6 h-16 flex items-center justify-between">
    <div class="flex items-center">
        <button type="button" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 p-1 -ms-1 me-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" @click="sidebarOpen = !sidebarOpen" aria-label="Toggle navigation">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        @hasSection('title')
            <h1 class="text-base sm:text-lg md:text-xl font-semibold truncate max-w-[140px] xs:max-w-[200px] sm:max-w-xs md:max-w-none">@yield('title')</h1>
        @endif
    </div>
    <div class="flex items-center gap-2">
        @yield('topbar_actions')
        <form method="POST" action="{{ route('locale.set', 'ar') }}" class="inline">
            @csrf
            <button type="submit" class="px-2 py-1 text-xs font-medium rounded {{ app()->getLocale() === 'ar' ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">ع</button>
        </form>
        <form method="POST" action="{{ route('locale.set', 'en') }}" class="inline">
            @csrf
            <button type="submit" class="px-2 py-1 text-xs font-medium rounded {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">EN</button>
        </form>
        <button type="button"
                class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                @click="toggleTheme()"
                aria-label="Toggle theme">
            <svg class="w-5 h-5 theme-sun-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <svg class="w-5 h-5 theme-moon-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        </button>

        @auth
            <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-1"></div>
            <div class="flex items-center gap-2">
                <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hidden md:inline truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            title="{{ __('app.auth.logout') }}"
                            class="p-2 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors"
                            aria-label="{{ __('app.auth.logout') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        @endauth
    </div>
</div>
