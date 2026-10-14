@php
    $isDark = request()->cookie('dark_mode') === 'true';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
      class="{{ $isDark ? 'dark bg-gray-900' : 'bg-gray-50' }}"
      style="{{ $isDark ? 'background-color: #111827; color-scheme: dark;' : 'background-color: #f9fafb; color-scheme: light;' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'POS System')</title>
    <script>
        (function() {
            try {
                var d = localStorage.getItem('dark_mode');
                var isDark = (d === 'true' || (d === null && window.matchMedia('(prefers-color-scheme: dark)').matches));
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
    @livewireStyles
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen" x-data="{
          dark: localStorage.getItem('dark_mode') !== null ? (localStorage.getItem('dark_mode') === 'true') : document.documentElement.classList.contains('dark'),
          sidebarOpen: false,
          sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true',
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
          },
          toggleSidebar() {
              this.sidebarCollapsed = !this.sidebarCollapsed;
              try {
                  localStorage.setItem('sidebar_collapsed', this.sidebarCollapsed ? 'true' : 'false');
              } catch (e) {}
          }
      }"
      x-init="
          window.addEventListener('storage', (e) => {
              if (e.key === 'dark_mode') {
                  const isDark = e.newValue === 'true';
                  dark = isDark;
                  document.documentElement.classList.toggle('dark', isDark);
                  document.cookie = 'dark_mode=' + e.newValue + ';path=/;max-age=31536000;SameSite=Lax';
              }
              if (e.key === 'sidebar_collapsed') {
                  sidebarCollapsed = e.newValue === 'true';
              }
          });
      ">
    <div class="flex min-h-screen">
        <x-layout.sidebar />
        <div class="flex-1 min-w-0 w-full flex flex-col min-h-screen transition-[margin] duration-300"
            :style="window.innerWidth >= 1024 ? 'margin-inline-start:' + (sidebarCollapsed ? '4rem' : '16rem') : 'margin-inline-start:0'">
            <x-layout.topbar />
            <main class="flex-1 p-4 md:p-6 lg:p-8">
                @if(session('success'))
                    <x-ui.alert type="success" :message="session('success')" />
                @endif
                @if(session('error'))
                    <x-ui.alert type="error" :message="session('error')" />
                @endif
                @if(session('warning'))
                    <x-ui.alert type="warning" :message="session('warning')" />
                @endif
                @yield('content')
            </main>
        </div>
    </div>
@livewireScripts
    
    <script src="{{ asset('js/expandable-image.js') }}"></script>
</body>

</html>