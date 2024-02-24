<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Sales by Product Category' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="sales_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Sales by Product Category</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 13px;">From </span>
                    <span style="font-weight: 500;color: #455a64;font-family: sans-serif;font-size: 13px;">
                        {{  carbon($request['fromDate'])->format('M d, Y') }}
                    </span>
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 13px;">To </span>
                    <span style="font-weight: 500;color: #455a64;font-family: sans-serif;font-size: 13px;">
                        {{  carbon($request['toDate'])->format('M d, Y') }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
    <div style="margin-top: 10px;"></div>
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;color: #fff;text-align: left;width: 40%;padding-left: 10px;">PRODUCT DETAILS
                </th>
                <th style="text-align: left;color: #fff;width: 30%;">QTY</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;width: 30%;">AMOUNT</th>
            </tr>
            </thead>
            <tbody>
            @if($categories)
                @foreach($categories as $key => $category)
                    <tr>
                        <td style="padding-left: 10px; padding-top: 10px;" colspan="3">
                            <b>{{ $category->name ?? 'Others' }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8">
                            <hr>
                        </td>
                    </tr>
                    @foreach($category->products as $product)
                        <tr>
                            <td style="vertical-align: middle;width: 14%;height: 35px;padding-left: 10px;">
                                {{ $product->name }}
                            </td>
                            <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $product->salesOrders->pluck('pivot')->sum('quantity') }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 14%;padding-right: 10px;">{{ $product->name }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;border-right: 1px solid #D0D0D0;">
                            <strong>Total</strong>
                        </td>
                        <td style="border-top: 1px solid #D0D0D0;width: 14%;border-right: 1px solid #D0D0D0;">
                            <strong>{{ $category->products->pluck('salesOrders')
                            ->collapse()->pluck('pivot')->sum('quantity') }}</strong>
                        </td>
                        <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;padding-right: 10px;">
                            <strong>{{ number_format($category->products->pluck('salesOrders')
                            ->collapse()->sum('total')) }}</strong>
                        </td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td
                    style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;border-right: 1px solid #D0D0D0;">
                    <strong>Grand Total</strong>
                </td>
                <td style="border-top: 1px solid #D0D0D0;width: 14%;border-right: 1px solid #D0D0D0;">
                    <strong>{{ $quantity_total }}</strong>
                </td>
                <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;padding-right: 10px;">
                    <strong>{{ number_format($order_total) }}</strong>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>