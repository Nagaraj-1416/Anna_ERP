<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Reimbursements' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="purchase_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Reimbursements</span>
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
    <div style="margin-top: 10px;"></div>
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;color: #fff;text-align: left;width: 10%;padding-left: 10px;">REPORT#</th>
                <th style="text-align: left;color: #fff;width: 12%;">FROM</th>
                <th style="text-align: left;color: #fff;width: 11%;">TO</th>
                <th style="text-align: left;color: #fff;width: 10%;">STATUS</th>
                <th style="text-align: left;color: #fff;width: 12%;">REIMBURSE DATE</th>
                <th style="text-align: right;color: #fff;width: 15%;padding-right: 10px;">AMOUNT</th>
                <th style="text-align: right;color: #fff;width: 15%;padding-right: 10px;">AMOUNT</th>
                <th style="text-align: right;color: #fff;width: 15%;padding-right: 10px;">BALANCE</th>
            </tr>
            </thead>
            <tbody>
            @if($reimburses)
                @foreach($reimburses as $key => $values)
                    @if($values->count())
                        <tr>
                            <td style="padding-left: 10px; padding-top: 10px;" colspan="8">
                                <b>{{ \App\User::find($key)->name ?? 'None' }}</b></td>
                        </tr>
                        <tr>
                            <td colspan="8">
                                <hr>
                            </td>
                        </tr>
                        @foreach($values as $key => $reimburse)
                            <tr>
                                <td style="vertical-align: middle;width: 10%;height: 35px;padding-left: 10px;">{{ $reimburse->report->report_no ?? 'None' }}</td>
                                <td style="vertical-align: middle;width: 12%;height: 35px;padding-left: 10px;">{{ $reimburse->report->report_from ?? 'None' }}</td>
                                <td style="vertical-align: middle;width: 11%;height: 35px;padding-left: 10px;">{{ $reimburse->report->report_to ?? 'None' }}</td>
                                <td style="vertical-align: middle;width: 10%;height: 35px;padding-left: 10px;">{{ $reimburse->report->status ?? 'None' }}</td>
                                <td style="vertical-align: middle;width: 12%;height: 35px;padding-left: 10px;">{{ $reimburse->reimbursed_on }}</td>
                                <td style="vertical-align: middle;text-align: right;width: 15%;height: 35px;padding-left: 10px;">{{ number_format($reimburse->report->amount ?? 0)}}</td>
                                <td style="vertical-align: middle;text-align: right;width: 15%;height: 35px;padding-left: 10px;">{{ number_format($reimburse->amount) }}</td>
                                <td style="vertical-align: middle;text-align: right;width: 15%;padding-right: 10px;">{{ number_format(($reimburse->report->amount ?? 0) - $reimburse->amount) }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endif
            <tr>
                <td colspan="5"
                    style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                    <strong>Total</strong>
                </td>
                <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;">
                    <strong>{{ number_format($report_total) }}</strong>
                </td>
                <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;">
                    <strong>{{ number_format($reimburses_total) }}</strong>
                </td>
                <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;padding-right: 10px;">
                    <strong>{{ number_format($balance) }}</strong>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>