@extends('layouts.app', ['title' => __('app.products.catalog')])

@section('content')
    <x-layout.breadcrumb :items="[
        ['label' => __('app.products.title'), 'url' => route('products.index')],
        ['label' => __('app.products.catalog')],
    ]" />
    <div class="max-w-6xl mx-auto space-y-6" x-data="productCatalogComponent()">


        <!-- Header -->
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('app.products.catalog_title') }}</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('app.products.catalog_desc') }}</p>
        </div>

        @if (session('error'))
            <x-ui.alert type="danger" :message="session('error')" />
        @endif

        <!-- Filter Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
            <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                {{ __('app.common.filter') ?? 'Filter Criteria' }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        {{ __('app.products.category') }}
                    </label>
                    <select x-model="selectedCategory" @change="filterCategoryChanged"
                        class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('app.products.all_categories') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name_ar }} @if ($cat->name_en)
                                    ({{ $cat->name_en }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Brand Filter -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        {{ __('app.products.brand') }}
                    </label>
                    <select x-model="selectedBrand" @change="filterBrandChanged"
                        class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('app.products.all_brands') }}</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand }}">{{ $brand }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Type -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        {{ __('app.products.price_type') }}
                    </label>
                    <select x-model="priceType"
                        class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="retail">{{ __('app.products.retail_price') }}</option>
                        <option value="wholesale">{{ __('app.products.wholesale_price') }}</option>
                        <option value="both">{{ __('app.products.both_prices') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products Selection & Table Card -->
        <div
            class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <!-- Table Header Toolbar -->
            <div
                class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button type="button" @click="toggleAllVisible"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors"
                        :class="allVisibleSelected
                            ?
                            'bg-blue-50 text-blue-700 border-blue-300 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-600' :
                            'bg-slate-100 text-slate-700 border-slate-300 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600 hover:bg-slate-200 dark:hover:bg-slate-600'">
                        <span
                            x-text="allVisibleSelected ? '{{ __('app.products.deselect_all') }}' : '{{ __('app.products.select_all') }}'"></span>
                    </button>

                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200">
                        <span x-text="selectedProductIds.length"></span>
                        <span class="ms-1">{{ __('app.products.selected_products') }}</span>
                    </span>
                </div>

                <!-- Instant Search Input -->
                <div class="relative w-full sm:w-64">
                    <svg class="absolute start-3 top-2.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" x-model="search" placeholder="{{ __('app.products.search_placeholder') }}"
                        class="w-full ps-9 pe-3 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Products Table -->
            <div class="overflow-x-auto max-h-[520px] overflow-y-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 text-start w-12">
                                <input type="checkbox" :checked="allVisibleSelected" @click="toggleAllVisible"
                                    class="w-4 h-4 rounded text-blue-600 border-slate-300 dark:border-slate-600 focus:ring-blue-500 cursor-pointer">
                            </th>
                            <th
                                class="px-4 py-3 text-start text-xs font-medium text-slate-500 dark:text-slate-400 uppercase w-16">
                                {{ __('app.products.image') }}
                            </th>
                            <th
                                class="px-4 py-3 text-start text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">
                                {{ __('app.common.code') }}
                            </th>
                            <th
                                class="px-4 py-3 text-start text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">
                                {{ __('app.common.name_ar') }}
                            </th>
                            <th
                                class="px-4 py-3 text-start text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">
                                {{ __('app.products.brand') }}
                            </th>
                            <th
                                class="px-4 py-3 text-start text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">
                                {{ __('app.products.category') }}
                            </th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-slate-500 dark:text-slate-400 uppercase"
                                x-show="priceType === 'retail' || priceType === 'both'">
                                {{ __('app.products.retail_price') }}
                            </th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-slate-500 dark:text-slate-400 uppercase"
                                x-show="priceType === 'wholesale' || priceType === 'both'">
                                {{ __('app.products.wholesale_price') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                        <template x-for="p in filteredProducts" :key="p.id">
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
                                :class="selectedProductIds.includes(p.id) ? 'bg-blue-50/50 dark:bg-blue-900/10' : ''"
                                @click="toggleProduct(p.id)">
                                <td class="px-4 py-3" @click.stop>
                                    <input type="checkbox" :value="p.id"
                                        :checked="selectedProductIds.includes(p.id)" @change="toggleProduct(p.id)"
                                        class="w-4 h-4 rounded text-blue-600 border-slate-300 dark:border-slate-600 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="px-4 py-3">
                                    <div
                                        class="w-10 h-10 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center">
                                        <template x-if="p.image && !p.image.includes('Temp') && !p.image.includes('/tmp/')">
                                            <img :src="p.image.startsWith('http') ? p.image : (p.image.startsWith('images/') ?
                                                '/' + p.image : '/storage/' + p.image)"
                                                :alt="p.name_ar" class="w-full h-full object-cover"
                                                onerror="this.onerror=null;this.src='/images/store.png';">
                                        </template>
                                        <template x-if="!p.image || p.image.includes('Temp') || p.image.includes('/tmp/')">
                                            <img src="/images/store.png" alt="Product"
                                                class="w-full h-full object-cover opacity-70">
                                        </template>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-white" x-text="p.code">
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-800 dark:text-slate-100">
                                    <span x-text="p.name_ar"></span>
                                    <span x-show="p.name_en" class="text-xs text-slate-400 block" x-text="p.name_en"></span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300" x-text="p.brand || '-'">
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300"
                                    x-text="p.category_name || '-'"></td>
                                <td class="px-4 py-3 text-sm font-mono text-slate-900 dark:text-white"
                                    x-show="priceType === 'retail' || priceType === 'both'">
                                    <span x-text="Number(p.retail_price || 0).toFixed(2)"></span>
                                </td>
                                <td class="px-4 py-3 text-sm font-mono text-slate-900 dark:text-white"
                                    x-show="priceType === 'wholesale' || priceType === 'both'">
                                    <span x-text="Number(p.wholesale_price || 0).toFixed(2)"></span>
                                </td>
                            </tr>
                        </template>

                        <!-- Empty State Row -->
                        <tr x-show="filteredProducts.length === 0">
                            <td colspan="8" class="px-6 py-12 text-center">
                                <x-ui.empty-state :title="__('app.products.no_products_for_catalog')" :description="__('app.products.no_found_desc')" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Download Form & Footer Bar -->
            <form method="POST" action="{{ route('products.catalog.download') }}"
                @submit="isSubmitting = true; setTimeout(() => { isSubmitting = false; }, 4000);">
                @csrf
                <input type="hidden" name="category_id" :value="selectedCategory">
                <input type="hidden" name="brand" :value="selectedBrand">
                <input type="hidden" name="price_type" :value="priceType">
                <input type="hidden" name="select_all" :value="selectAll ? '1' : '0'">

                <template x-for="id in selectedProductIds" :key="id">
                    <input type="hidden" name="product_ids[]" :value="id">
                </template>

                <div
                    class="px-6 py-4 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-700 flex flex-wrap items-center justify-between gap-4">
                    <div class="text-sm text-slate-600 dark:text-slate-400">
                        <span x-text="selectedProductIds.length"></span>
                        <span class="ms-1">{{ __('app.products.selected_products') }}</span>
                        <span x-show="selectedProductIds.length === 0" class="text-amber-500 ms-2">
                            ({{ __('app.common.select_at_least_one') }})
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('products.index') }}"
                            class="px-4 py-2 text-sm font-medium rounded-lg text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                            {{ __('app.common.cancel') }}
                        </a>

                        <button type="submit" :disabled="selectedProductIds.length === 0 || isSubmitting"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-500 disabled:opacity-40 disabled:cursor-not-allowed shadow-sm transition-colors">
                            <template x-if="!isSubmitting">
                                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </template>
                            <template x-if="isSubmitting">
                                <svg class="animate-spin -ms-1 me-2 h-4 w-4 text-white inline"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </template>
                            <span
                                x-text="isSubmitting ? '{{ __('app.common.loading') }}' : '{{ __('app.products.generate_catalog') }}'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Raw Data for Alpine -->
    <script type="application/json" id="catalog-products-json">
        {!! json_encode($productsData, JSON_UNESCAPED_UNICODE) !!}
    </script>

    <script>
        function productCatalogComponent() {
            return {
                selectedCategory: '',
                selectedBrand: '',
                search: '',
                priceType: 'retail',
                selectAll: true,
                selectedProductIds: [],
                isSubmitting: false,
                products: [],

                get filteredProducts() {
                    return this.products.filter(p => {
                        if (this.selectedCategory && String(p.category_id) !== String(this.selectedCategory)) {
                            return false;
                        }
                        if (this.selectedBrand && String(p.brand) !== String(this.selectedBrand)) {
                            return false;
                        }
                        if (this.search) {
                            const q = this.search.toLowerCase();
                            const matchAr = p.name_ar && p.name_ar.toLowerCase().includes(q);
                            const matchEn = p.name_en && p.name_en.toLowerCase().includes(q);
                            const matchCode = p.code && p.code.toLowerCase().includes(q);
                            if (!matchAr && !matchEn && !matchCode) {
                                return false;
                            }
                        }
                        return true;
                    });
                },

                get allVisibleSelected() {
                    const visible = this.filteredProducts;
                    if (visible.length === 0) return false;
                    return visible.every(p => this.selectedProductIds.includes(p.id));
                },

                toggleAllVisible() {
                    const visible = this.filteredProducts;
                    if (this.allVisibleSelected) {
                        const visibleIds = new Set(visible.map(p => p.id));
                        this.selectedProductIds = this.selectedProductIds.filter(id => !visibleIds.has(id));
                        this.selectAll = false;
                    } else {
                        const set = new Set(this.selectedProductIds);
                        visible.forEach(p => set.add(p.id));
                        this.selectedProductIds = Array.from(set);
                        this.selectAll = (this.selectedProductIds.length === this.products.length);
                    }
                },

                toggleProduct(id) {
                    const idx = this.selectedProductIds.indexOf(id);
                    if (idx > -1) {
                        this.selectedProductIds.splice(idx, 1);
                        this.selectAll = false;
                    } else {
                        this.selectedProductIds.push(id);
                        if (this.selectedProductIds.length === this.products.length) {
                            this.selectAll = true;
                        }
                    }
                },

                filterCategoryChanged() {
                    this.syncSelectionToFiltered();
                },

                filterBrandChanged() {
                    this.syncSelectionToFiltered();
                },

                syncSelectionToFiltered() {
                    const visible = this.filteredProducts;
                    this.selectedProductIds = visible.map(p => p.id);
                    this.selectAll = (this.selectedCategory === '' && this.selectedBrand === '' && !this.search);
                },

                init() {
                    try {
                        const raw = document.getElementById('catalog-products-json').textContent;
                        this.products = JSON.parse(raw) || [];
                    } catch (e) {
                        this.products = [];
                    }
                    this.selectedProductIds = this.products.map(p => p.id);
                }
            };
        }
    </script>
@endsection
