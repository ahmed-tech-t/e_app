@props([])

@php
$currentRoute = request()->route() ? request()->route()->getName() : '';
$navItems = [
    ['label' => __('app.nav.dashboard'), 'route' => '/', 'icon' => 'dashboard', 'active' => $currentRoute === '' || request()->path() === '/'],
    ['type' => 'section', 'label' => __('app.sections.inventory')],
    ['label' => __('app.nav.products'), 'route' => '/products', 'icon' => 'product', 'active' => str_starts_with($currentRoute, 'products.')],
    ['label' => __('app.nav.categories'), 'route' => '/categories', 'icon' => 'category', 'active' => str_starts_with($currentRoute, 'categories.')],
    ['label' => __('app.nav.sale_units'), 'route' => '/sale-units', 'icon' => 'unit', 'active' => str_starts_with($currentRoute, 'sale-units.')],
    ['label' => __('app.nav.locations'), 'route' => '/locations', 'icon' => 'location', 'active' => str_starts_with($currentRoute, 'locations.')],
    ['type' => 'section', 'label' => __('app.sections.people')],
    ['label' => __('app.nav.suppliers'), 'route' => '/suppliers', 'icon' => 'supplier', 'active' => str_starts_with($currentRoute, 'suppliers.')],
    ['type' => 'section', 'label' => __('app.sections.transactions')],
    ['label' => __('app.nav.purchases'), 'route' => '/purchases', 'icon' => 'purchase', 'active' => str_starts_with($currentRoute, 'purchases.')],
    ['label' => __('app.nav.sales'), 'route' => '/sales', 'icon' => 'sale', 'active' => str_starts_with($currentRoute, 'sales.')],
    ['label' => __('app.nav.sales_returns'), 'route' => '/sales-returns', 'icon' => 'sale_return', 'active' => str_starts_with($currentRoute, 'sales-returns.')],
    ['label' => __('app.nav.purchase_returns'), 'route' => '/purchase-returns', 'icon' => 'purchase_return', 'active' => str_starts_with($currentRoute, 'purchase-returns.')],
    ['type' => 'section', 'label' => __('app.sections.stock')],
    ['label' => __('app.nav.movements'), 'route' => '/stock', 'icon' => 'stock', 'active' => str_starts_with($currentRoute, 'stock.')],
    ['label' => __('app.nav.transfer'), 'route' => '/stock/transfer', 'icon' => 'transfer', 'active' => $currentRoute === 'stock.transfer'],
];
@endphp

<aside class="fixed inset-y-0 ltr:left-0 rtl:right-0 z-50 bg-gray-800 dark:bg-gray-950 text-white transition-all duration-300 ltr:-translate-x-full rtl:translate-x-full lg:ltr:translate-x-0 lg:rtl:translate-x-0"
       :class="{ '!translate-x-0': sidebarOpen }"
       :style="window.innerWidth >= 1024 ? (sidebarCollapsed ? 'width: 4rem' : 'width: 16rem') : 'width: 16rem; max-width: 80vw;'"
       @resize.window="if (window.innerWidth >= 1024) { sidebarOpen = false; }"
       @keydown.escape.window="sidebarOpen = false"
>
    <div class="flex flex-col h-full">
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-700">
            <span class="text-xl font-bold" x-show="!sidebarCollapsed || window.innerWidth < 1024">POS</span>
            <button type="button" class="lg:hidden text-gray-400 hover:text-white p-1.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" @click="sidebarOpen = false" aria-label="Close menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto overscroll-contain py-4">
            @foreach($navItems as $item)
                @if(($item['type'] ?? null) === 'section')
                    <div class="px-4 mt-4 mb-2 first:mt-0" x-show="!sidebarCollapsed || window.innerWidth < 1024">
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
                            @case('sale_return')
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                @break
                            @case('purchase_return')
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"/></svg>
                                @break
                            @case('transfer')
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                @break
                        @endswitch
                        <span class="ms-3" x-show="!sidebarCollapsed || window.innerWidth < 1024">{{ $item['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="hidden lg:block border-t border-gray-700 p-4">
            <button type="button"
                    class="flex items-center text-gray-400 hover:text-white w-full focus:outline-none focus:ring-2 focus:ring-gray-600 rounded-lg"
                    :class="{ 'justify-center': sidebarCollapsed }"
                    @click="toggleSidebar()">
                @if(app()->getLocale() === 'ar')
                    {{-- Arabic / RTL (Sidebar is on the right edge) --}}
                    {{-- When expanded: arrow points right (>> into the right margin to collapse) --}}
                    {{-- When collapsed: arrow points left (<< outward to expand) --}}
                    <svg class="w-5 h-5 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!sidebarCollapsed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                        <path x-show="sidebarCollapsed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                @else
                    {{-- English / LTR (Sidebar is on the left edge) --}}
                    {{-- When expanded: arrow points left (<< into the left margin to collapse) --}}
                    {{-- When collapsed: arrow points right (>> outward to expand) --}}
                    <svg class="w-5 h-5 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!sidebarCollapsed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                        <path x-show="sidebarCollapsed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                @endif
                <span class="ms-3 text-sm" x-show="!sidebarCollapsed">{{ __('app.nav.collapse') }}</span>
            </button>
        </div>
    </div>
</aside>

<div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/60 z-40 lg:hidden" @click="sidebarOpen = false"></div>
