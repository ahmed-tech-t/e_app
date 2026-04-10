<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'POS System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen" x-data="{
          dark: localStorage.getItem('dark_mode') === 'true',
          sidebarOpen: false,
          sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true'
      }" x-init="document.documentElement.classList.toggle('dark', dark)"
    x-effect="document.documentElement.classList.toggle('dark', dark); localStorage.setItem('dark_mode', dark)">
    <div class="flex min-h-screen"
        x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true' }">
        <x-layout.sidebar />
        <div class="flex-1 flex flex-col min-h-screen transition-all duration-300"
            :style="window.innerWidth >= 1024 ? 'margin-left:' + (sidebarCollapsed ? '4rem' : '16rem') : 'margin-left:0'">
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
</body>

</html>