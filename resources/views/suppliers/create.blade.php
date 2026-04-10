@extends('layouts.app', ['title' => 'Create Supplier'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Suppliers', 'url' => route('suppliers.index')], ['label' => 'Create']]" />

    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('suppliers.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input name="name" label="Name" :required="true" />
                <x-ui.input name="email" label="Email" type="email" />
                <x-ui.input name="phone" label="Phone" />
                <x-ui.input name="address" label="Address" />
            </div>
            <x-form.form-actions :cancelUrl="route('suppliers.index')" />
        </form>
    </div>
@endsection
