@extends('layouts.app', ['title' => __('app.suppliers.edit')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.suppliers.title'), 'url' => route('suppliers.index')], ['label' => __('app.common.edit')]]" />

    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input name="name" :label="__('app.common.name')" :value="$supplier->name" :required="true" />
                <x-ui.input name="email" :label="__('app.suppliers.email')" type="email" :value="$supplier->email" />
                <x-ui.input name="phone" :label="__('app.common.phone')" :value="$supplier->phone" />
                <x-ui.input name="address" :label="__('app.common.address')" :value="$supplier->address" />
            </div>
            <x-form.form-actions :cancelUrl="route('suppliers.index')" :submitLabel="__('app.common.save')" />
        </form>
    </div>
@endsection
