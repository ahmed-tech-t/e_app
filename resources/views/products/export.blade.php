@extends('layouts.app', ['title' => __('app.common.export_products')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.products.title'), 'url' => route('products.index')], ['label' => __('app.common.export')]]" />

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

            <!-- Header -->
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('app.common.export_products') }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Choose which columns to include in the exported file.</p>
            </div>

            <form method="POST" action="{{ route('products.export.download') }}"
                x-data="{
                    all: true,
                    cols: @js(array_fill_keys(array_keys($columns), true)),
                    toggleAll() {
                        this.all = !this.all;
                        Object.keys(this.cols).forEach(k => this.cols[k] = this.all);
                    },
                    syncAll() {
                        this.all = Object.values(this.cols).every(v => v);
                    }
                }">
                @csrf

                <!-- Columns Grid -->
                <div class="px-6 py-5">

                    <!-- Select All toggle -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Columns</span>
                        <button type="button" @click="toggleAll"
                            class="text-xs font-medium text-blue-500 hover:text-blue-400 transition-colors"
                            x-text="all ? 'Deselect All' : 'Select All'">
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        @foreach ($columns as $key => $label)
                            <label class="flex items-center gap-3 px-4 py-3 rounded-lg border cursor-pointer transition-colors"
                                :class="cols['{{ $key }}']
                                    ? 'bg-blue-50 border-blue-300 dark:bg-blue-900/20 dark:border-blue-600'
                                    : 'bg-slate-50 border-slate-200 dark:bg-slate-700/40 dark:border-slate-600'">
                                <input type="checkbox" name="columns[]" value="{{ $key }}"
                                    x-model="cols['{{ $key }}']"
                                    @change="syncAll"
                                    class="w-4 h-4 rounded text-blue-600 border-slate-300 dark:border-slate-500 focus:ring-blue-500 cursor-pointer">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <!-- Warning when nothing selected -->
                    <p x-show="!Object.values(cols).some(v => v)"
                        class="mt-3 text-xs text-amber-500 dark:text-amber-400">
                        Select at least one column to export.
                    </p>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-3">
                    <a href="{{ route('products.index') }}"
                        class="px-4 py-2 text-sm font-medium rounded-lg text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                        {{ __('app.common.cancel') }}
                    </a>
                    <button type="submit"
                        :disabled="!Object.values(cols).some(v => v)"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-500 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        {{ __('app.common.export') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
