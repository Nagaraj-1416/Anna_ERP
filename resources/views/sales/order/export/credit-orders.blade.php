<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Credit Orders' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="purchase_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Credit Orders</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 10px;">
                            <b>Route:</b> {{ $route->name.' ('.$route->code.')' }}
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
                <th style="height: 20px;color: #fff;text-align: left;padding-left: 10px;width: 22%;">Order no</th>
                <th style="text-align: left;color: #fff;width: 12%;">Order date</th>
                <th style="text-align: left;color: #fff;">Customer</th>
                <th style="text-align: right;color: #fff;width: 10%;">Amount</th>
                <th style="text-align: right;color: #fff;width: 10%;">Paid</th>
                <th style="text-align: right;color: #fff;width: 10%;padding-right: 10px;">Balance</th>
            </tr>
            </thead>
            <tbody>
            @if($orders)
                @foreach($orders as $key => $order)
                    <tr>
                        <td style="vertical-align: middle;height: 20px;padding-left: 10px;text-align: left;width: 22%;">{{ $order->ref ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 12%;">{{ $order->order_date ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: left;"> {{ $order->customer->display_name ?? 'None'}}</td>
                        <td style="vertical-align: middle;text-align: right;width: 10%;">{{ number_format($order->total ?? '0', 2)  }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 10%;">{{ number_format( $order->payments->where('status', 'Paid')->sum('payment') ?? '0', 2) }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 10%;padding-right: 10px;">
                            {{ number_format( $order->total - $order->payments->where('status', 'Paid')->sum('payment') , 2)  }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3"
                        style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                        <strong>Total</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;border-right: 1px solid #D0D0D0;">
                        <strong>{{ number_format($order_total, 2) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;border-right: 1px solid #D0D0D0;">
                        <strong>{{ number_format($payment_total, 2) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;padding-right: 10px;">
                        <strong>{{ number_format($balance, 2) }}</strong>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>