@extends('layouts.app', ['title' => 'Create Sale Unit'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Sale Units', 'url' => route('sale-units.index')], ['label' => 'Create']]" />

    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('sale-units.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input name="name_ar" label="Name (Arabic)" :required="true" />
                <x-ui.input name="name_en" label="Name (English)" />
            </div>
            <x-form.form-actions :cancelUrl="route('sale-units.index')" />
        </form>
    </div>
@endsection
