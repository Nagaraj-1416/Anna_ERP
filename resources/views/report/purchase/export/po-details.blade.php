<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Purchase Order Details' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="purchase_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Purchase Order Details</span>
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
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    @if(isset($orders))
        <div>
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                <thead>
                <tr style="background-color: #2f3d4a;">
                    <th style="height: 35px;color: #fff;text-align: left;padding-left: 10px;">SUPPLIER</th>
                    <th style="height: 35px;color: #fff;text-align: left;width: 12%;padding-left: 10px;">ORDER#</th>
                    <th style="text-align: left;color: #fff;width: 12%;">DATE</th>
                    <th style="text-align: left;color: #fff;width: 12%;">DELIVERY</th>
                    <th style="text-align: left;color: #fff;width: 10%;">STATUS</th>
                    <th style="text-align: right;color: #fff;width: 12%;">AMOUNT</th>
                    <th style="text-align: right;color: #fff;width: 12%;">MADE</th>
                    <th style="text-align: right;color: #fff;padding-right: 10px;width: 12%;">BALANCE</th>
                </tr>
                </thead>
                <tbody>
                @if($orders)
                    @foreach($orders as $orderKey => $order)
                        <tr>
                            <td style="vertical-align: middle;height: 35px;padding-left: 10px;">
                                {{ $order->supplier->display_name ?? 'None' }}
                            </td>
                            <td style="vertical-align: middle;height: 35px;padding-left: 10px;width: 12%;">
                                {{ $order->po_no }}
                            </td>
                            <td style="vertical-align: middle;text-align: left;width: 12%;">{{ $order->order_date }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 12%;">{{ $order->delivery_date }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $order->status }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 12%;">{{ number_format($order->total) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 12%;">{{ number_format(poOutstanding($order)['paid']) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 12%;padding-right: 10px;">{{ number_format(poOutstanding($order)['balance'])}}</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td colspan="5"
                        style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                        <strong>Total</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 15px">
                        <strong>{{ number_format($order_total) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 15px">
                        <strong>{{ number_format($payment_total) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 15px;padding-right: 10px;">
                        <strong>{{ number_format($balance) }}</strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    @endif
</div>
</body>
</html>