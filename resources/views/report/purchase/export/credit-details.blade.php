<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Credit Details' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="purchase_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Credit Details</span>
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
                <th style="height: 35px;color: #fff;text-align: left;width: 15%;padding-left: 10px;">CODE</th>
                <th style="text-align: left;color: #fff;width: 15%;">CREDIT DATE</th>
                <th style="text-align: left;color: #fff;width: 10%;">STATUS</th>
                <th style="text-align: right;color: #fff;width: 15%;">CREDIT</th>
                <th style="text-align: right;color: #fff;width: 15%;">REFUNDED</th>
                <th style="text-align: right;color: #fff;width: 15%;">USED CREDIT</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;width: 15%;">BALANCE</th>
            </tr>
            </thead>
            <tbody>
            @if($credits->count())
                @foreach($credits as $key => $values)
                    <tr>
                        <td style="padding-left: 10px; padding-top: 10px;" colspan="7">
                            <b>{{ \App\Supplier::find($key)->display_name ?? 'None' }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="7">
                            <hr>
                        </td>
                    </tr>
                    @foreach($values as $creditKey => $credit)
                        <tr>
                            <td style="vertical-align: middle;width: 15%;height: 35px;padding-left: 10px;">
                                {{ $credit->code ?? '' }}
                            </td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $credit->date }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $credit->status }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 15%;">{{ number_format($credit->amount) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 15%;">{{ number_format($credit->refunds->sum('amount')) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 15%;">{{ number_format($credit->payments->sum('payment'))}}</td>
                            <td style="vertical-align: middle;text-align: right;width: 15%;padding-right: 10px;">
                                {{
                               number_format($credit->amount - ($credit->refunds->sum('amount')  + $credit->payments->sum('payment')))
                                }}
                            </td>
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
                <td colspan="3"
                    style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                    <strong>Total</strong>
                </td>
                <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 15px;">
                    <strong>{{ number_format($credits_total) }}</strong>
                </td>
                <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 15px;">
                    <strong>{{ number_format($refunded_total) }}</strong>
                </td>
                <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 15px;">
                    <strong>{{ number_format($payment_total) }}</strong>
                </td>
                <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 15px;padding-right: 10px;">
                    <strong>{{ number_format($balance) }}</strong>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>