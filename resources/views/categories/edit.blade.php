@extends('layouts.app', ['title' => __('app.categories.edit')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.categories.title'), 'url' => route('categories.index')], ['label' => __('app.common.edit')]]" />

    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('categories.update', $category->id) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input name="name_ar" :label="__('app.common.name_ar')" :value="$category->name_ar" :required="true" />
                <x-ui.input name="name_en" :label="__('app.common.name_en')" :value="$category->name_en" :required="true" />
            </div>
            <x-form.form-actions :cancelUrl="route('categories.index')" :submitLabel="__('app.common.update')" />
        </form>
    </div>
@endsection
