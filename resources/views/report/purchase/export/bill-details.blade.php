<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Bill Details' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="purchase_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Bill Details</span>
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
    @if(isset($bills))
        <div>
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                <thead>
                <tr style="background-color: #2f3d4a;">
                    <th style="height: 35px;color: #fff;text-align: left;padding-left: 10px;">SUPPLIER</th>
                    <th style="height: 35px;color: #fff;text-align: left;width: 10%;padding-left: 12px;">BILL#</th>
                    <th style="text-align: left;color: #fff;width: 14%;">DATE</th>
                    <th style="text-align: left;color: #fff;width: 14%;">DUE DATE</th>
                    <th style="text-align: left;color: #fff;width: 15%;">STATUS</th>
                    <th style="text-align: right;color: #fff;width: 15%;">AMOUNT</th>
                    <th style="text-align: right;color: #fff;padding-right: 10px;width: 10%;">BALANCE</th>
                </tr>
                </thead>
                <tbody>
                @if($bills)
                    @foreach($bills as $key => $bill)
                        <tr>
                            <td style="vertical-align: middle;width: 15%;height: 35px;padding-left: 10px;">
                                {{ $bill->supplier->display_name ?? 'None' }}
                            </td>
                            <td style="vertical-align: middle;width: 12%;height: 35px;padding-left: 10px;">
                                {{ $bill->bill_no }}
                            </td>
                            <td style="vertical-align: middle;text-align: left;width: 14%;">{{ $bill->bill_date }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 14%;">{{ $bill->due_date }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $bill->status }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 15%;">{{ number_format($bill->amount) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 15%;padding-right: 10px;">{{ number_format($bill->amount - $bill->payments->sum('payment'))}}</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td colspan="5"
                        style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                        <strong>Total</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 15px">
                        <strong>{{ number_format($bill_total) }}</strong>
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