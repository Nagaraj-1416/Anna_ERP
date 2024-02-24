<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Estimate Details' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="purchase_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Estimate Details</span>
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
    @if(isset($estimates))
        <div>
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                <thead>
                <tr style="background-color: #2f3d4a;">
                    <th style="height: 35px;color: #fff;text-align: left;width: 15%;padding-left: 10px;">CUSTOMER</th>
                    <th style="height: 35px;color: #fff;text-align: left;width: 15%;padding-left: 10px;">ESTIMATE#
                    </th>
                    <th style="text-align: left;color: #fff;width: 15%;">DATE</th>
                    <th style="text-align: left;color: #fff;width: 15%;">EXPIRY DATE</th>
                    <th style="text-align: left;color: #fff;width: 20%;">STATUS</th>
                    <th style="text-align: right;color: #fff;width: 20%;padding-right: 10px;">AMOUNT</th>
                </tr>
                </thead>
                <tbody>
                @if($estimates)
                    @foreach($estimates as $key => $estimate)
                        <tr>
                            <td style="vertical-align: middle;width: 15%;height: 35px;padding-left: 10px;">
                                {{ $estimate->customer->display_name ?? 'None' }}
                            </td>
                            <td style="vertical-align: middle;width: 15%;height: 35px;padding-left: 10px;">
                                {{ $estimate->estimate_no }}
                            </td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $estimate->estimate_date }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $estimate->expiry_date }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 20%;">{{ $estimate->status }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 20%;padding-right: 10px;">{{ number_format($estimate->total) }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td colspan="5"
                        style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                        <strong>Grand Total</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 20px;padding-right: 10px;">
                        <strong>{{ number_format($estimate_total) }}</strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    @endif
</div>
</body>
</html>