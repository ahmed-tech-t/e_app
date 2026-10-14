@extends('layouts.app', ['title' => __('app.locations.edit')])

@section('content')
    <x-layout.breadcrumb :items="[['label' => __('app.locations.title'), 'url' => route('locations.index')], ['label' => __('app.common.edit')]]" />

    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('locations.update', $location->id) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input name="name" :label="__('app.common.name')" :value="$location->name" :required="true" />
                <x-ui.input name="address" :label="__('app.common.address')" :value="$location->address" />
                <x-ui.input name="phone" :label="__('app.common.phone')" :value="$location->phone" />
                <x-ui.select name="type" :label="__('app.locations.type')" :required="true" :options="[['id' => 'store', 'name' => __('app.locations.store')], ['id' => 'warehouse', 'name' => __('app.locations.sales_point')]]" :selected="$location->type" valueKey="id" labelKey="name" />
            </div>
            <x-form.form-actions :cancelUrl="route('locations.index')" :submitLabel="__('app.common.save')" />
        </form>
    </div>
@endsection
