<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Supplier Balances' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="purchase_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Supplier Balances</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 13px;">As of</span>
                    <span style="font-weight: 500;color: #455a64;font-family: sans-serif;font-size: 13px;">
                        {{  carbon($request['date'])->format('M d, Y') }}
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
                <th style="height: 35px;color: #fff;text-align: left;padding-left: 10px;">SUPPLIER</th>
                <th style="height: 35px;color: #fff;text-align: right;text-transform:uppercase;width: 20%;">AMOUNT
                </th>
                <th style="height: 35px;color: #fff;text-align: right;text-transform:uppercase;width: 20%;">BILLED</th>
                <th style="height: 35px;color: #fff;text-align: right;text-transform:uppercase;width: 20%;">MADE</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;width: 20%;">TOTAL</th>
            </tr>
            </thead>
            <tbody>
            @if($suppliers)
                @foreach($suppliers as $key => $supplier)
                    <tr>
                        <td style="vertical-align: middle;width: 20%;height: 35px;padding-left: 10px;">
                            {{ $supplier->display_name }}
                        </td>
                        <td style="vertical-align: middle;text-align: right;width: 20%;">{{ number_format($supplier->orders->sum('total')) }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 20%;">{{ number_format($supplier->bills->sum('amount')) }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 20%;">{{ number_format($supplier->payments->sum('payment')) }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 20%;padding-right: 10px;">{{ number_format(($supplier->orders->sum('total') - $supplier->payments->sum('payment'))) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                        <strong>Total</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14px">
                        <strong>{{ number_format($po_total) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14px">
                        <strong>{{ number_format($bill_total) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14px">
                        <strong>{{ number_format($payment_total) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14px;padding-right: 10px;">
                        <strong>{{ number_format($balance) }}</strong>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>