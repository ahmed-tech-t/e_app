@php
    $isDark = request()->cookie('dark_mode') === 'true';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    class="{{ $isDark ? 'dark bg-gray-900' : 'bg-gray-50' }}"
    style="{{ $isDark ? 'background-color: #111827; color-scheme: dark;' : 'background-color: #f9fafb; color-scheme: light;' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('app.auth.login') }} - {{ config('app.name', 'POS') }}</title>
    <script>
        (function() {
            try {
                var d = localStorage.getItem('dark_mode');
                var isDark = (d === 'true' || (d === null && window.matchMedia('(prefers-color-scheme: dark)')
                .matches));
                if (isDark) {
                    document.documentElement.classList.add('dark');
                    document.documentElement.classList.add('bg-gray-900');
                    document.documentElement.classList.remove('bg-gray-50');
                    document.documentElement.style.backgroundColor = '#111827';
                    document.documentElement.style.colorScheme = 'dark';
                } else {
                    document.documentElement.classList.remove('dark');
                    document.documentElement.classList.remove('bg-gray-900');
                    document.documentElement.classList.add('bg-gray-50');
                    document.documentElement.style.backgroundColor = '#f9fafb';
                    document.documentElement.style.colorScheme = 'light';
                }
                document.cookie = 'dark_mode=' + (isDark ? 'true' : 'false') + ';path=/;max-age=31536000;SameSite=Lax';
            } catch (e) {}
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col justify-between"
    x-data="{
        dark: localStorage.getItem('dark_mode') !== null ? (localStorage.getItem('dark_mode') === 'true') : document.documentElement.classList.contains('dark'),
        toggleTheme() {
            this.dark = !this.dark;
            document.documentElement.classList.toggle('dark', this.dark);
            document.documentElement.classList.toggle('bg-gray-900', this.dark);
            document.documentElement.classList.toggle('bg-gray-50', !this.dark);
            document.documentElement.style.backgroundColor = this.dark ? '#111827' : '#f9fafb';
            document.documentElement.style.colorScheme = this.dark ? 'dark' : 'light';
            try {
                localStorage.setItem('dark_mode', this.dark ? 'true' : 'false');
                document.cookie = 'dark_mode=' + (this.dark ? 'true' : 'false') + ';path=/;max-age=31536000;SameSite=Lax';
            } catch (e) {}
        }
    }">

    {{-- Top bar utilities: Language switcher & Theme toggle --}}
    <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-end gap-2">
        <form method="POST" action="{{ route('locale.set', 'ar') }}" class="inline">
            @csrf
            <button type="submit"
                class="px-2.5 py-1 text-xs font-semibold rounded-md transition-colors {{ app()->getLocale() === 'ar' ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800' }}">عربي</button>
        </form>
        <form method="POST" action="{{ route('locale.set', 'en') }}" class="inline">
            @csrf
            <button type="submit"
                class="px-2.5 py-1 text-xs font-semibold rounded-md transition-colors {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800' }}">EN</button>
        </form>
        <button type="button"
            class="p-2 rounded-lg text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
            @click="toggleTheme()" aria-label="Toggle theme">
            <svg class="w-5 h-5 theme-sun-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg class="w-5 h-5 theme-moon-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </header>

    {{-- Main Container --}}
    <main class="w-full max-w-md mx-auto px-4 py-8 flex-1 flex flex-col justify-center">
        <div
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none p-6 sm:p-8">
            {{-- Brand Header --}}
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-500/30 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ __('app.auth.sign_in') }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('app.auth.enter_credentials') }}
                </p>
            </div>

            {{-- Flash Messages --}}
            @if (session('status'))
                <x-ui.alert type="info" :message="session('status')" />
            @endif

            @if (session('error'))
                <x-ui.alert type="error" :message="session('error')" />
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                @csrf

                <div>
                    <x-ui.input type="email" name="email" :label="__('app.auth.email')" :value="old('email')"
                        placeholder="admin@example.com" required autofocus />
                </div>

                <div>
                    <x-ui.input type="password" name="password" :label="__('app.auth.password')" placeholder="••••••••" required />
                </div>

                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}
                            class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-gray-700 dark:checked:bg-blue-600">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('app.auth.remember_me') }}</span>
                    </label>
                </div>

                <div>
                    <x-ui.button type="submit" variant="primary"
                        class="w-full py-2.5 text-base justify-center shadow-md shadow-blue-500/20">
                        {{ __('app.auth.sign_in') }}
                    </x-ui.button>
                </div>
            </form>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="w-full max-w-7xl mx-auto px-4 py-4 text-center text-xs text-gray-500 dark:text-gray-500">
        &copy; {{ date('Y') }} POS System.
    </footer>

</body>

</html>
