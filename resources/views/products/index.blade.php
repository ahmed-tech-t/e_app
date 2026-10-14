@extends('layouts.app', ['title' => __('app.products.title')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.products.title')]]" />

    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('app.products.title') }}</h2>

        <div x-data="{ open: false, importOpen: false }" class="flex items-center gap-3">

            <!-- Primary Action -->
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-500 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('app.products.add_new') }}
            </a>

            <!-- Dropdown for Bulk Actions -->
            <div class="relative inline-block text-left">
                <button @click="open = !open" @click.outside="open = false" type="button"
                    class="inline-flex items-center px-3.5 py-2 text-sm font-medium rounded-lg text-slate-300 bg-slate-800 hover:bg-slate-700 border border-slate-700 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-500">
                    <svg class="w-4 h-4 me-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                    {{ __('app.common.more') }}
                    <svg class="w-4 h-4 ms-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="open" x-transition class="absolute end-0 z-10 mt-2 w-52 origin-top-right rounded-lg bg-slate-800 border border-slate-700 shadow-lg py-1">
                    <!-- Import -->
                    <button @click="importOpen = true; open = false" type="button"
                        class="w-full flex items-center px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                        <svg class="w-4 h-4 me-2.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        {{ __('app.common.import_products') }}
                    </button>

                    <div class="border-t border-slate-700 my-1"></div>

                    <!-- Export -->
                    <a href="{{ route('products.export') }}" target="_blank"
                        class="w-full flex items-center px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                        <svg class="w-4 h-4 me-2.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        {{ __('app.common.export_products') }}
                    </a>
                </div>
            </div>

            <!-- Import Modal -->
            <div x-show="importOpen" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @keydown.escape.window="importOpen = false">

                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/60" @click="importOpen = false"></div>

                <!-- Card -->
                <div class="relative w-full max-w-lg rounded-xl bg-slate-800 border border-slate-700 shadow-2xl p-6"
                    x-data="{ showRef: false }">
                    <h3 class="text-lg font-semibold text-white mb-1">{{ __('app.common.import_products') }}</h3>
                    <p class="text-sm text-slate-400 mb-4">
                        {{ __('app.common.upload_file_desc') }}
                        <a href="{{ route('products.export-template') }}" class="text-blue-400 hover:underline">{{ __('app.common.download_template') }}</a>
                        {{ __('app.common.see_format_desc') }}
                    </p>

                    <!-- Collapsible column reference -->
                    <div class="mb-4 rounded-lg border border-slate-600 overflow-hidden">
                        <button type="button" @click="showRef = !showRef"
                            class="w-full flex items-center justify-between px-4 py-2.5 bg-slate-700/60 hover:bg-slate-700 transition-colors text-start">
                            <span class="text-sm font-medium text-slate-300">{{ __('app.common.accepted_columns') }}</span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="showRef ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="showRef" x-transition class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="bg-slate-700/40 text-slate-400">
                                        <th class="text-start px-4 py-2 font-medium w-1/3">{{ __('app.common.field') }}</th>
                                        <th class="text-start px-4 py-2 font-medium">{{ __('app.common.any_column_titles') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-700">
                                    @php
                                        $required = \App\Imports\ProductsImport::REQUIRED_FIELDS;
                                        $labels = [
                                            'code'             => __('app.common.code'),
                                            'original_code'    => __('app.products.barcode'),
                                            'name_ar'          => __('app.common.name_ar'),
                                            'name_en'          => __('app.common.name_en'),
                                            'brand'            => __('app.products.brand'),
                                            'origin'           => 'Origin',
                                            'description'      => 'Description',
                                            'units_per_carton' => 'Units / Carton',
                                            'category'         => __('app.products.category'),
                                            'sale_unit'        => __('app.products.sale_unit'),
                                        ];
                                    @endphp
                                    @foreach ($columnAliases as $field => $aliases)
                                        <tr class="text-slate-300">
                                            <td class="px-4 py-2 font-medium">
                                                {{ $labels[$field] ?? $field }}
                                                @if (in_array($field, $required))
                                                    <span class="text-red-400 ms-0.5">*</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-slate-400">{{ implode(', ', $aliases) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <p class="px-4 py-2 text-xs text-slate-500 italic">{{ __('app.common.required_notice') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-5">
                            <label class="block text-sm font-medium text-slate-300 mb-2">{{ __('app.common.file') }} <span class="text-red-400">*</span></label>
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                class="block w-full text-sm text-slate-300 file:me-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-slate-700 file:text-slate-200 hover:file:bg-slate-600 cursor-pointer border border-slate-600 rounded-lg bg-slate-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('file')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="importOpen = false"
                                class="px-4 py-2 text-sm font-medium rounded-lg text-slate-300 bg-slate-700 hover:bg-slate-600 transition-colors">
                                {{ __('app.common.cancel') }}
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-500 transition-colors">
                                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                {{ __('app.common.import') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <livewire:search.product-search />
@endsection
