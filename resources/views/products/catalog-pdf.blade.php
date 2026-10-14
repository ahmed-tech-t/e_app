<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Product Catalog</title>
    <style>
        @page {
            margin: 1cm;
        }

        @if(file_exists(storage_path('fonts/arial.ttf')))
        @font-face {
            font-family: 'ArialCustom';
            src: url("{{ storage_path('fonts/arial.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }
        @endif

        body {
            font-family: 'ArialCustom', 'DejaVu Sans', sans-serif;
            direction: ltr; /* Ar-PHP handles the RTL reversal */
            text-align: right;
            margin: 0;
            padding: 0;
            margin-bottom: 50px;
            color: #1e293b;
        }

        .product-page {
            page-break-after: always;
            width: 100%;
        }

        .product-page:last-child {
            page-break-after: auto;
        }

        .title {
            text-align: center;
            font-size: 22pt;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 2px solid #334155;
            padding-bottom: 10px;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .image-container {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
            min-height: 200px;
        }

        .full-image {
            max-width: 95%;
            height: auto;
            max-height: 420px;
            border-radius: 8px;
        }

        .details-table {
            width: 100%;
            margin-left: auto;
            border-collapse: collapse;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
        }

        .details-table td {
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            text-align: right;
        }

        .label {
            background-color: #f1f5f9;
            width: 32%;
            font-size: 14pt;
            font-weight: bold;
            color: #334155;
        }

        .value {
            font-size: 14pt;
            color: #0f172a;
        }

        .price-value {
            color: #2563eb;
            font-weight: bold;
            font-size: 15pt;
        }

        footer {
            position: fixed;
            bottom: -15px;
            left: 0px;
            right: 0px;
            height: 45px;
            text-align: center;
            font-size: 10pt;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }

        .page-number:after {
            content: counter(page) " {{ $arabic->utf8Glyphs('الصفحة') }} ";
        }
    </style>
</head>

<body>
    <footer>
        <span class="page-number"></span>
    </footer>

    @foreach($products as $item)
        <div class="product-page">
            <h1 class="title">{{ $item['title'] ?? 'Product' }}</h1>

            <div class="image-container">
                @if(!empty($item['image_path']))
                    <img src="{{ $item['image_path'] }}" class="full-image">
                @elseif(!empty($item['image_base64']))
                    <img src="{{ $item['image_base64'] }}" class="full-image">
                @endif
            </div>

            <table class="details-table">
                @if(!empty($item['code']))
                <tr>
                    <td class="value">{{ $item['code'] }}</td>
                    <td class="label">{{ $arabic->utf8Glyphs("الكود") }}</td>
                </tr>
                @endif

                @if(!empty($item['original_code']))
                <tr>
                    <td class="value">{{ $item['original_code'] }}</td>
                    <td class="label">{{ $arabic->utf8Glyphs("الباركود") }}</td>
                </tr>
                @endif

                @if(!empty($item['brand']))
                <tr>
                    <td class="value">{{ $item['brand'] }}</td>
                    <td class="label">{{ $arabic->utf8Glyphs("العلامة التجارية") }}</td>
                </tr>
                @endif

                @if(!empty($item['category']))
                <tr>
                    <td class="value">{{ $item['category'] }}</td>
                    <td class="label">{{ $arabic->utf8Glyphs("الفئة") }}</td>
                </tr>
                @endif

                @if(!empty($item['size']))
                <tr>
                    <td class="value">{{ $item['size'] }}</td>
                    <td class="label">{{ $arabic->utf8Glyphs("المقاس") }}</td>
                </tr>
                @endif

                @if(!empty($item['unit']))
                <tr>
                    <td class="value">{{ $item['unit'] }}</td>
                    <td class="label">{{ $arabic->utf8Glyphs("الوحدة") }}</td>
                </tr>
                @endif

                @if($priceType === 'retail' || $priceType === 'both')
                <tr>
                    <td class="value price-value">
                        {{ number_format((float) ($item['retail_price'] ?? 0), 2) }} {{ $arabic->utf8Glyphs("جنيه") }}
                    </td>
                    <td class="label">{{ $arabic->utf8Glyphs($priceType === 'both' ? "سعر التجزئة" : "السعر") }}</td>
                </tr>
                @endif

                @if($priceType === 'wholesale' || $priceType === 'both')
                <tr>
                    <td class="value price-value">
                        {{ number_format((float) ($item['wholesale_price'] ?? 0), 2) }} {{ $arabic->utf8Glyphs("جنيه") }}
                    </td>
                    <td class="label">{{ $arabic->utf8Glyphs($priceType === 'both' ? "سعر الجملة" : "السعر") }}</td>
                </tr>
                @endif
            </table>
        </div>
    @endforeach
</body>

</html>

