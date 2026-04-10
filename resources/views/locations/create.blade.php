@extends('layouts.app', ['title' => 'Create Location'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Locations', 'url' => route('locations.index')], ['label' => 'Create']]" />

    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('locations.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input name="name" label="Name" :required="true" />
                <x-ui.input name="address" label="Address" />
                <x-ui.input name="phone" label="Phone" />
                <x-ui.select name="type" label="Type" :required="true" :options="[['id' => 'store', 'name' => 'Store'], ['id' => 'warehouse', 'name' => 'Warehouse']]" valueKey="id" labelKey="name" />
            </div>
            <x-form.form-actions :cancelUrl="route('locations.index')" />
        </form>
    </div>
@endsection
