<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Payment Made' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="purchase_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Payment Made</span>
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
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;color: #fff;text-align: left;width: 15%;padding-left: 10px;">BILL#</th>
                <th style="text-align: left;color: #fff;width: 15%;">PAYMENT DATE</th>
                <th style="text-align: left;color: #fff;width: 15%;">TYPE</th>
                <th style="text-align: left;color: #fff;width: 15%;">MODE</th>
                <th style="text-align: left;color: #fff;width: 10%;">STATUS</th>
                <th style="text-align: left;color: #fff;width: 15%;">PAID THROUGH</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;width: 15%;">PAYMENT</th>
            </tr>
            </thead>
            <tbody>
            @if($payments->count())
                @foreach($payments as $key => $values)
                    <tr>
                        <td style="padding-left: 10px; padding-top: 10px;" colspan="7">
                            <b>{{ \App\Supplier::find($key)->display_name ?? 'None' }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="7">
                            <hr>
                        </td>
                    </tr>
                    @foreach($values as $paymentKey => $payment)
                        <tr>
                            <td style="vertical-align: middle;width: 15%;height: 35px;padding-left: 10px;">
                                {{ $payment->bill->bill_no ?? '' }}
                            </td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $payment->payment_date }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $payment->payment_type }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $payment->payment_mode }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $payment->status }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $payment->paidThrough->name ?? 'None' }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 15%;padding-right: 10px;">{{ number_format($payment->payment)}}</td>
                        </tr>
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td style="padding-left: 10px; padding-top: 10px;" colspan="7">
                        <p>No data to display...</p>
                    </td>
                </tr>
            @endif
            <tr>
                <td colspan="6"
                    style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                    <strong>Total</strong>
                </td>
                <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14px;padding-right: 10px;">
                    <strong>{{ number_format($payments_total) }}</strong>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>