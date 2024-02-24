<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Daily Sales Credit orders ('.$allocation->code.')' }}</title>
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
                        Associated Credit Orders | As at {{ carbon()->now()->format('F j, Y') }}
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 14px;font-family: sans-serif;">{{ $allocation->code }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div>
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
                <th style="height: 20px;color: #fff;text-align: left;padding-left: 10px;width: 15%;">Customer</th>
                <th style="text-align: left;color: #fff;width: 15%;">Order no</th>
                <th style="text-align: left;color: #fff;width: 10%;">Order date</th>
                <th style="text-align: right;color: #fff;width: 10%;">Amount</th>
                <th style="text-align: right;color: #fff;width: 10%;">Paid</th>
                <th style="text-align: right;color: #fff;width: 10%;padding-right: 10px;">Balance</th>
            </tr>
            </thead>
            <tbody>
            @if($orders)
                @foreach($orders as $key => $order)
                    <tr>
                        <td style="vertical-align: middle;height: 20px;padding-left: 10px; width: 15%;"> {{ $order->customer->display_name ?? 'None'}}</td>
                        <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $order->order->ref ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $order->order->order_date ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 10%;">{{ number_format($order->order->total ?? '0', 2)  }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 10%;">{{ number_format( $order->order->payments->where('status', 'Paid')->sum('payment') ?? '0', 2) }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 10%;padding-right: 10px;">
                            {{ number_format( $order->order->total - $order->order->payments->where('status', 'Paid')->sum('payment') , 2)  }}
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
                        <strong>{{ number_format($order_total - $payment_total, 2) }}</strong>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
