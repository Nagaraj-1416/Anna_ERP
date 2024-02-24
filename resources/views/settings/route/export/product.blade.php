<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Route Products ('. $route->code .')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="salesAllocation">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Route</span><br/>
                    <span style="font-weight: 700;color: #455a64;font-size: 12px;font-family: sans-serif;">
                        Associated Products
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">{{ $route->code }}</span>
                </td>
            </tr>
        </table>
    </div>
    <hr style="margin-bottom: 10px;">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;color: #fff;text-align: left;padding-left: 10px;">Product code</th>
                <th style="text-align: left;color: #fff;width: 20%;">Product Name</th>
                <th style="text-align: left;color: #fff;width: 20%;">Type</th>
                <th style="text-align: center;color: #fff;width: 20%;">Available Qty</th>
                <th style="text-align: center;color: #fff;width: 20%;">Default Allocation Qty</th>
            </tr>
            </thead>
            <tbody>
            @if($products)
                @foreach($products as $key => $product)
                    <tr>
                        <td style="vertical-align: middle;height: 35px;padding-left: 10px;"> {{ $product->code }}</td>
                        <td style="vertical-align: middle;">{{ $product->name }}</td>
                        <td style="vertical-align: middle;text-align: left;">{{ $product->type }}</td>
                        <td style="vertical-align: middle;text-align: center;">{{ $product->stock->available_stock ?? 0}}</td>
                        <td style="vertical-align: middle;text-align: center;">{{ $product->pivot->default_qty ?? 0  }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>