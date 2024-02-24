<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Daily Sales Products ('.$allocation->code.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 10px;" id="salesAllocation">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 14px;font-family: sans-serif;">SALES ALLOCATION</span><br/>
                    <span style="font-weight: 700;color: #455a64;font-size: 10px;font-family: sans-serif;">
                        <b>Associated Products:</b> {{ count($products) }} | As at {{ carbon()->now()->format('F j, Y') }}
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 14px;font-family: sans-serif;">{{ $allocation->code }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0;"></div>
    <div >
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 10px;">
                            <b>Allocation period:</b> {{ $allocation->from_date }} to {{ $allocation->to_date }}<br>
                        </span>
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;"></td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 10px;">
                            <b>Route:</b> {{ $allocation->route->name.' ('.$allocation->route->code.')' }}
                            <br>
                        </span>
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;"></td>
            </tr>
            <tr>
                <td style="width: 30%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 10px;">
                            <b>Vehicle:</b> {{ $allocation->vehicle->vehicle_no }} <br>
                        </span>
                    </div>
                </td>
                <td style="width: 30%;font-family: sans-serif;">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 10px;">
                            <b>Rep:</b> {{ $allocation->rep->name.' ('.$allocation->rep->code.')' }}
                            <br>
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 10px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 20px;color: #fff;text-align: left;padding-left: 10px;">Products</th>
                <th style="text-align: center;color: #fff;width: 10%;">CF Qty</th>
                <th style="text-align: center;color: #fff;width: 10%;">Allocated Qty</th>
                <th style="text-align: center;color: #fff;width: 10%;">Sold Qty</th>
                <th style="text-align: center;color: #fff;width: 10%;">Replaced Qty</th>
                <th style="text-align: center;color: #fff;width: 5%;">Returned Qty</th>
                <th style="text-align: center;color: #fff;width: 5%;">Shortage Qty</th>
                <th style="text-align: center;color: #fff;width: 5%;">Excess Qty</th>
                <th style="text-align: center;color: #fff;width: 5%;">Damaged Qty</th>
                <th style="text-align: center;color: #fff;width: 10%;">Restored Qty</th>
                <th style="text-align: center;color: #fff;width: 10%;padding-right: 10px;">Available Qty</th>
            </tr>
            </thead>
            <tbody>
            @if($products)
                @foreach($products as $key => $product)
                    <tr>
                        <td style="vertical-align: middle;height: 20px;padding-left: 10px;"> {{ $product->product->name }}</td>
                        <td style="vertical-align: middle;text-align: center;width: 5%;">{{ $product->cf_qty ?? 0 }}</td>
                        <td style="vertical-align: middle;text-align: center;width: 10%;">{{ $product->quantity ?? 0}}</td>
                        <td style="vertical-align: middle;text-align: center;width: 5%;">{{ $product->sold_qty ?? 0}}</td>
                        <td style="vertical-align: middle;text-align: center;width: 10%;">{{ $product->replaced_qty ?? 0}}</td>
                        <td style="vertical-align: middle;text-align: center;width: 10%;">{{ $product->returned_qty ?? 0}}</td>
                        <td style="vertical-align: middle;text-align: center;width: 10%;">{{ $product->shortage_qty ?? 0}}</td>
                        <td style="vertical-align: middle;text-align: center;width: 6%;">{{ $product->excess_qty ?? 0}}</td>
                        <td style="vertical-align: middle;text-align: center;width: 10%;">{{ $product->damaged_qty ?? 0}}</td>
                        <td style="vertical-align: middle;text-align: center;width: 10%;">{{ $product->restored_qty?? 0 }}</td>
                        <td style="vertical-align: middle;text-align: center;width: 10%;">
                            {{ getAvailableQty($product) }}
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