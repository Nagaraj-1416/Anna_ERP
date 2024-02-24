<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Daily Sales ('.$allocation->code.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="salesAllocation">
    @if($handover)
        @include('sales.allocation.export.handover')
        <hr style="margin-bottom: 10px;">
    @endif
        @include('sales.allocation.export.allocation')
        <hr style="margin-bottom: 10px;">
        <div>
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
                <tr>
                    <td style="width: 50%;font-family: sans-serif;" align="left">
                        <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">CUSTOMERS</span>
                    </td>
                    <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">
                        Total Customers: {{ $customers->count() }}
                    </span>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                <thead>
                <tr style="background-color: #2f3d4a;">
                    <th style="height: 35px;color: #fff;text-align: left;width: 15%;padding-left: 10px;">Code#</th>
                    <th style="text-align: left;color: #fff;width: 15%;">Name</th>
                    <th style="text-align: left;color: #fff;width: 15%;">Phone</th>
                    <th style="text-align: left;color: #fff;width: 15%;padding-right: 10px;">Mobile</th>
                </tr>
                </thead>
                <tbody>
                @if($customers)
                    @foreach($customers as $key => $customer)
                        <tr>
                            <td style="vertical-align: middle;width: 15%;height: 35px;padding-left: 10px;">{{ $customer->customer->code ?? 'None' }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $customer->customer->display_name ?? 'None'}}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $customer->customer->phone ?? 'None' }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $customer->customer->mobile ?? 'None' }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>

        <hr style="margin-bottom: 10px;">

        <div>
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
                <tr>
                    <td style="width: 50%;font-family: sans-serif;" align="left">
                        <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">PRODUCTS</span>
                    </td>
                    <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">
                        Total Products: {{ $products->count() }}
                    </span>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                <thead>
                <tr style="background-color: #2f3d4a;">
                    <th style="height: 35px;color: #fff;text-align: left;padding-left: 10px;">Product details</th>
                    <th style="text-align: left;color: #fff;width: 15%;">Type</th>
                    <th style="text-align: left;color: #fff;width: 15%;">Store</th>
                    <th style="text-align: left;color: #fff;width: 10%;">Carry forward Qty</th>
                    <th style="text-align: left;color: #fff;width: 10%;">Allocated Qty</th>
                    <th style="text-align: left;color: #fff;width: 10%;">Sold Qty</th>
                    <th style="text-align: left;color: #fff;width: 10%;">Restored Qty</th>
                    <th style="text-align: left;color: #fff;width: 10%;padding-right: 10px;">Available Qty</th>
                </tr>
                </thead>
                <tbody>
                @if($products)
                    @foreach($products as $key => $product)
                        <tr>
                            <td style="vertical-align: middle;height: 35px;padding-left: 10px;"> {{ $product->product->name.' ('.$product->product->code.')' }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $product->product->type }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $product->store->name }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $product->cf_qty ?? 0 }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $product->quantity ?? 0}}</td>
                            <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $product->sold_qty ?? 0}}</td>
                            <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $product->restored_qty?? 0 }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 10%;">
                                {{ ($product->quantity + $product->cf_qty) - ($product->sold_qty + $product->restored_qty) }}
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
</div>
</body>
</html>