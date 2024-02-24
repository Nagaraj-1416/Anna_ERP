<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Store Stocks Details' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="purchase_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Store Stocks Details</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 500;color: #455a64;font-family: sans-serif;font-size: 13px;">
                         {{ $store['name'] }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 13px;">As at Date -  </span>
                    <span style="font-weight: 500;color: #455a64;font-family: sans-serif;font-size: 13px;">
                         {{  carbon($request['date'])->format('M d, Y') }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
    @if(isset($stocks))
        <div>
            <div style="margin-top: 10px;"></div>
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                <thead>
                <tr style="background-color: #2f3d4a;">
                    <th style="height: 35px;color: #fff;text-align: left;padding-left: 12px;">Products</th>
                    <th style="text-align: left;color: #fff;width: 15%;">Min stock level</th>
                    <th style="text-align: left;color: #fff;width: 15%;">IN Stock</th>
                    <th style="height: 35px;color: #fff;text-align: left;width: 15%;padding-left: 10px;">OUT Stock</th>
                    <th style="text-align: right;color: #fff;padding-right: 10px;width: 15%;">Available</th>
                </tr>
                </thead>
                <tbody>
                @if($stocks)
                    @foreach($stocks as $key => $stock)
                        <tr>
                            <td style="vertical-align: middle;height: 35px;padding-left: 10px;">
                                {{ $stock->product->name }}
                            </td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $stock->min_stock_level }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $stock->in_stock_as_at }}</td>
                            <td style="vertical-align: middle;width: 15%;height: 35px;padding-left: 10px;">
                                {{ $stock->out_stock_as_at }}
                            </td>
                            <td style="vertical-align: middle;text-align: right;width: 15%;padding-right: 10px;">{{ $stock->stock_as_at }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    @endif
    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 11px; margin-top: 50px">
        <tr>
            <td style="width: 35%;">
                <div>
                    <span style="border-top: 1px dotted; width: auto; padding: 10px">Authorized Signature</span>
                </div>
            </td>
            <td style="width: 35%;">
                <div>
                    <span style="border-top: 1px dotted; width: auto; padding: 10px">Store Keeper's Signature</span>
                </div>
            </td>
        </tr>
    </table>
</div>
</body>
</html>