@props([])

@php
$currentRoute = request()->route() ? request()->route()->getName() : '';
$navItems = [
    ['label' => 'Dashboard', 'route' => '/', 'icon' => 'dashboard', 'active' => $currentRoute === '' || request()->path() === '/'],
    ['type' => 'section', 'label' => 'Inventory'],
    ['label' => 'Products', 'route' => '/products', 'icon' => 'product', 'active' => str_starts_with($currentRoute, 'products.')],
    ['label' => 'Categories', 'route' => '/categories', 'icon' => 'category', 'active' => str_starts_with($currentRoute, 'categories.')],
    ['label' => 'Sale Units', 'route' => '/sale-units', 'icon' => 'unit', 'active' => str_starts_with($currentRoute, 'sale-units.')],
    ['label' => 'Locations', 'route' => '/locations', 'icon' => 'location', 'active' => str_starts_with($currentRoute, 'locations.')],
    ['type' => 'section', 'label' => 'People'],
    ['label' => 'Suppliers', 'route' => '/suppliers', 'icon' => 'supplier', 'active' => str_starts_with($currentRoute, 'suppliers.')],
    ['type' => 'section', 'label' => 'Transactions'],
    ['label' => 'Purchases', 'route' => '/purchases', 'icon' => 'purchase', 'active' => str_starts_with($currentRoute, 'purchases.')],
    ['label' => 'Sales', 'route' => '/sales', 'icon' => 'sale', 'active' => str_starts_with($currentRoute, 'sales.')],
    ['type' => 'section', 'label' => 'Stock'],
    ['label' => 'Movements', 'route' => '/stock', 'icon' => 'stock', 'active' => str_starts_with($currentRoute, 'stock.')],
    ['label' => 'Transfer', 'route' => '/stock/transfer', 'icon' => 'transfer', 'active' => $currentRoute === 'stock.transfer'],
];
@endphp

<aside class="fixed inset-y-0 left-0 z-50 bg-gray-800 dark:bg-gray-950 text-white transition-all duration-300
             -translate-x-full lg:translate-x-0"
       :class="{ 'lg:w-16': sidebarCollapsed, 'lg:w-64': !sidebarCollapsed, 'w-64 translate-x-0': sidebarOpen }"
       x-init="$nextTick(() => {
           if (window.innerWidth >= 1024) {
               $el.style.width = JSON.parse(localStorage.getItem('sidebar_collapsed') || 'false') ? '4rem' : '16rem';
           }
       })"
       @resize.window="
           if (window.innerWidth >= 1024) {
               $el.style.width = sidebarCollapsed ? '4rem' : '16rem';
               sidebarOpen = false;
           }
       "
       style="width: 16rem;"
>
    <div class="flex flex-col h-full">
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-700">
            <span class="text-xl font-bold" x-show="!sidebarCollapsed">POS</span>
            <span class="text-xl font-bold lg:hidden" x-show="sidebarCollapsed === false || window.innerWidth < 1024">POS</span>
            <button class="lg:hidden text-gray-400 hover:text-white" @click="sidebarOpen = false">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-4">
            @foreach($navItems as $item)
                @if(($item['type'] ?? null) === 'section')
                    <div class="px-4 mt-4 mb-2 first:mt-0" x-show="!sidebarCollapsed">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $item['label'] }}</span>
                    </div>
                @else
                    <a href="{{ $item['route'] }}"
                       class="flex items-center px-4 py-2.5 text-sm transition-colors duration-200 {{ $item['active'] ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                       title="{{ $item['label'] }}">
                        @switch($item['icon'])
                            @case('dashboard')
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                @break
                            @case('product')
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                @break
                            @case('category')
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                @break
                            @case('unit')
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                                @break
                            @case('location')
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                @break
                            @case('supplier')
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                @break
                            @case('purchase')
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                                @break
                            @case('sale')
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @break
                            @case('stock')
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                @break
                            @case('transfer')
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                @break
                        @endswitch
                        <span class="ml-3" x-show="!sidebarCollapsed">{{ $item['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="hidden lg:block border-t border-gray-700 p-4">
            <button class="flex items-center text-gray-400 hover:text-white w-full" @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebar_collapsed', sidebarCollapsed)">
                <svg class="w-5 h-5 shrink-0 transition-transform" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                <span class="ml-3 text-sm" x-show="!sidebarCollapsed">Collapse</span>
            </button>
        </div>
    </div>
</aside>

<div x-show="sidebarOpen" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" @click="sidebarOpen = false"></div>
