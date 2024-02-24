<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Aging Details' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="sales_by_customer">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Aging Details</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 13px;">As of </span>
                    <span style="font-weight: 500;color: #455a64;font-family: sans-serif;font-size: 13px;">
                        {{  carbon($request['date'])->format('M d, Y') }}
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
                <th style="height: 35px;color: #fff;text-align: left;width: 12%;padding-left: 10px;">INVOICE#</th>
                <th style="height: 35px;color: #fff;text-align: left;padding-left: 15px;">CUSTOMER</th>
                <th style="text-align: left;color: #fff;width: 15%;">INVOICE DATE</th>
                <th style="text-align: left;color: #fff;width: 12%;">DUE DATE</th>
                <th style="text-align: left;color: #fff;width: 8%;">AGE</th>
                <th style="text-align: left;color: #fff;width: 10%;">STATUS</th>
                <th style="text-align: right;color: #fff;width: 12%;">AMOUNT</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;width: 12%;">BALANCE</th>
            </tr>
            </thead>
            <tbody>
            @if($data)
                @foreach($data as $key => $values)
                    @if($values->count())
                        <tr>
                            <td style="padding-left: 10px; padding-top: 10px;" colspan="8">
                                <b>{{ $key }}</b></td>
                        </tr>
                        <tr>
                            <td colspan="8">
                                <hr>
                            </td>
                        </tr>
                        @foreach($values as $paymentKey => $invoice)
                            <tr>
                                <td style="vertical-align: middle;width: 12%;height: 35px;padding-left: 10px;">
                                    {{ $invoice->invoice_no ?? '' }}
                                </td>
                                <td style="vertical-align: middle;text-align: left;">{{ $invoice->customer->display_name ?? 'None' }}</td>
                                <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $invoice->invoice_date }}</td>
                                <td style="vertical-align: middle;text-align: left;width: 12%;">{{ $invoice->due_date }}</td>
                                <td style="vertical-align: middle;text-align: left;width: 8%;">{{ $invoice->age }}</td>
                                <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $invoice->status }}</td>
                                <td style="vertical-align: middle;text-align: right;width: 12%;">{{ number_format($invoice->amount) }}</td>
                                <td style="vertical-align: middle;text-align: right;width: 12%;padding-right: 10px;">{{ number_format($invoice->amount - $invoice->payments->sum('payment'))}}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @else
                <tr>
                    <td style="padding-left: 10px; padding-top: 10px;" colspan="7">
                        <p>No data to display...</p>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>