<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Expense Reports Details' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="purchase_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Expense Reports Details</span>
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
                <th style="height: 35px;color: #fff;text-align: left;width: 12%;padding-left: 10px;">REPORT#</th>
                <th style="text-align: left;color: #fff;width: 12%;">START</th>
                <th style="text-align: left;color: #fff;width: 12%;">END</th>
                <th style="text-align: left;color: #fff;">TITLE</th>
                <th style="text-align: left;color: #fff;width: 18%;">SUBMITTED BY</th>
                <th style="text-align: left;color: #fff;width: 12%;">APPROVER</th>
                <th style="text-align: right;color: #fff;width: 15%;padding-right: 10px;">AMOUNT</th>
            </tr>
            </thead>
            <tbody>
            @if($reports)
                @foreach($reports as $key => $report)
                    <tr>
                        <td style="vertical-align: middle;width: 12%;height: 35px;padding-left: 10px;">{{ $report->report_no }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 12%;">{{ $report->report_from }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 12%;">{{ $report->report_to }}</td>
                        <td style="vertical-align: middle;text-align: left;">{{ $report->title }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 18%;">{{ $report->submittedBy->name ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 12%;">{{ $report->approvedBy->name ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 15%;padding-right: 10px;">{{ number_format($report->amount) }}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td colspan="6"
                    style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                    <strong>Total</strong>
                </td>
                <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;padding-right: 10px;">
                    <strong>{{ number_format($total) }}</strong>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>