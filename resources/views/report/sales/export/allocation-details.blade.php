<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Allocation Details' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 10px;" id="purchase_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Allocation Details</span>
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
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 13px;">{{ $rep ? $rep->name : '' }}</span>
                </td>
            </tr>
        </table>
    </div>
    @if(isset($allocations))
        <div>
            <div style="margin-top: 10px;"></div>
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 10px;">
                <thead>
                <tr style="background-color: #2f3d4a;">
                    <th style="height: 35px;color: #fff;text-align: left;width: 10%;padding-left: 10px;">ALLOCATION DETAILS</th>
                    <th style="text-align: right;color: #fff;width: 10%;">TOTAL SALES</th>
                    <th style="text-align: right;color: #fff;width: 10%;">RECEIVED</th>
                    <th style="text-align: right;color: #fff;width: 10%;">CASH RECEIVED</th>
                    <th style="text-align: right;color: #fff;width: 10%;">CHEQUE RECEIVED</th>
                    <th style="text-align: right;color: #fff;width: 10%;">BALANCE</th>
                    <th style="text-align: right;color: #fff;width: 10%;">EXPENSES</th>
                    <th style="text-align: right;color: #fff;width: 10%;">RETURNS</th>
                    <th style="text-align: right;color: #fff;width: 10%;">COLLECTION</th>
                    <th style="text-align: right;color: #fff;width: 10%;">CASH COLLECTION</th>
                    <th style="text-align: right;color: #fff;width: 10%;padding-right: 10px;">CHEQUE COLLECTION</th>
                </tr>
                </thead>
                <tbody>
                @if($allocations)
                    @foreach($allocations as $key => $allocation)
                        <tr style="padding-bottom: 15px;">
                            <td style="vertical-align: middle;text-align: left;width: 10%;height: 35px;padding-left: 10px;">
                                {{ $allocation->code }}<br>
                                {{ $allocation->from_date }}<br>
                                {{ $allocation->route->name }}
                            </td>
                            <td style="vertical-align: middle;text-align: right;width: 10%;">{{ number_format($allocation->total_sales) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 10%;">{{ number_format($allocation->received) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 10%;">{{ number_format($allocation->cash_received) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 10%;">{{ number_format($allocation->cheque_received) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 10%;">{{ number_format($allocation->balance) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 10%;">{{ number_format($allocation->expenses) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 10%;">{{ number_format($allocation->returns) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 10%;">{{ number_format($allocation->old_received) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 10%;">{{ number_format($allocation->old_cash_received) }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 10%;padding-right: 10px;">{{ number_format($allocation->old_cheque_received) }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                        <strong>Grand Total</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 10px"><strong>{{ number_format($allocation_total_sales) }}</strong></td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 10px"><strong>{{ number_format($allocation_received) }}</strong></td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 10px"><strong>{{ number_format($allocation_cash_received) }}</strong></td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 10px"><strong>{{ number_format($allocation_cheque_received) }}</strong></td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 10px"><strong>{{ number_format($allocation_balance) }}</strong></td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 10px"><strong>{{ number_format($allocation_expenses) }}</strong></td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 10px"><strong>{{ number_format($allocation_returns) }}</strong></td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 10px"><strong>{{ number_format($allocation_old_received) }}</strong></td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 10px"><strong>{{ number_format($allocation_old_cash_received) }}</strong></td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 10px;padding-right: 10px;"><strong>{{ number_format($allocation_old_cheque_received) }}</strong></td>
                </tr>
                </tbody>
            </table>
        </div>
    @endif
</div>
<!-- signatures panel -->
<div style="margin-top: 60px;">
    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 11px;">
        <tr>
            <td style="width: 35%; text-align: center;">
                <div>
                    <span style="border-top: 1px dotted; width: auto; padding: 10px">Authorized Signature</span>
                </div>
            </td>
            <td style="width: 35%; text-align: center;">
                <div>
                    <span style="border-top: 1px dotted; width: auto; padding: 10px">Rep's Signature</span>
                </div>
            </td>
        </tr>
    </table>
</div>
</body>
</html>