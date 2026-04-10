@extends('layouts.app', ['title' => 'Product Details'])

@section('content')
    <x-layout.breadcrumb :items="[['label' => 'Products', 'url' => route('products.index')], ['label' => $product->name_ar]]" />

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('products.edit', $product->id) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <x-ui.back-button :url="route('products.index')" />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Code</p>
                    <p class="font-medium">{{ $product->code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Name (Arabic)</p>
                    <p class="font-medium">{{ $product->name_ar }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Name (English)</p>
                    <p class="font-medium">{{ $product->name_en ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Brand</p>
                    <p class="font-medium">{{ $product->brand }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Original Code</p>
                    <p class="font-medium">{{ $product->original_code ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Origin</p>
                    <p class="font-medium">{{ $product->origin ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Category</p>
                    <p class="font-medium">{{ $product->category->name_ar ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sale Unit</p>
                    <p class="font-medium">{{ $product->sale_unit->name_ar ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Retail Price</p>
                    <p class="font-medium">{{ number_format($product->retail_price, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Wholesale Price</p>
                    <p class="font-medium">{{ number_format($product->wholesale_price, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Units per Carton</p>
                    <p class="font-medium">{{ $product->units_per_carton ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Description</p>
                    <p class="font-medium">{{ $product->description ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- @if(count($priceHistory) > 0)
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold mb-4">Price History</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Valid From</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Valid To</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($priceHistory as $price)
                        <tr>
                            <td class="px-4 py-3 text-sm">
                                <x-ui.badge :variant="$price->type->value === 'retail' ? 'info' : 'warning'"
                                    :text="ucfirst($price->type->value)" />
                            </td>
                            <td class="px-4 py-3 text-sm">{{ number_format($price->price, 2) }}</td>
                            <td class="px-4 py-3 text-sm">{{ $price->valid_from?->format('M d, Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $price->valid_to?->format('M d, Y H:i') ?? 'Current' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif --}}

        <div class="card">
            <div class="card-body">
                <div id="price-chart-container"></div>
            </div>
        </div>
@endsection


    <script>
        window.addEventListener('load', function () {
            if (typeof window.ApexCharts === 'undefined') {
                console.error("ApexCharts not loaded");
                return;
            }

            const rawData = @json($priceHistory ?? []);

            const options = {
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: {
                        show: true
                    }
                },

                series: [],

                xaxis: {
                    type: 'datetime',
                    labels: {
                        datetimeUTC: false,
                        format: 'dd MMM' // 👈 try: dd MMM, dd MMM yyyy
                    }
                },

                colors: ['#2563eb', '#f59e0b'], // Retail = blue, Wholesale = orange

                stroke: {
                    width: 3,
                    curve: 'smooth'
                },

                markers: {
                    size: 4
                },

                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val.toFixed(2);
                        }
                    }
                },

                noData: {
                    text: 'No price history available'
                },

            };

            const chart = new ApexCharts(
                document.querySelector("#price-chart-container"),
                options
            );

            chart.render();

            function loadChart() {
                if (!rawData.length) {
                    chart.updateSeries([]);
                    return;
                }

                const retail = rawData
                    .filter(item => item.type === 'retail')
                    .map(i => ({
                        x: new Date(i.valid_from).getTime(),
                        y: i.price
                    }));

                const wholesale = rawData
                    .filter(item => item.type === 'wholesale')
                    .map(i => ({
                        x: new Date(i.valid_from).getTime(),
                        y: i.price
                    }));

                chart.updateSeries([
                    {
                        name: 'Retail',
                        data: retail
                    },
                    {
                        name: 'Wholesale',
                        data: wholesale
                    }
                ]);
            }

            loadChart();

            // ✅ Optional: Dropdown toggle
            const toggle = document.getElementById('typeToggle');

            if (toggle) {
                toggle.addEventListener('change', function () {
                    const type = this.value;

                    if (type === 'retail') {
                        chart.hideSeries('Wholesale');
                        chart.showSeries('Retail');
                    } else if (type === 'wholesale') {
                        chart.hideSeries('Retail');
                        chart.showSeries('Wholesale');
                    }
                });
            }
        });
    </script>