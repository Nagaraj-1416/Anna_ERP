<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Aging Summary' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="purchase_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Aging Summary</span>
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
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;color: #fff;text-align: left;padding-left: 10px;">SUPPLIER</th>
                <th style="height: 35px;color: #fff;text-align: right;text-transform:uppercase;">1-30 DAYS</th>
                <th style="height: 35px;color: #fff;text-align: right;text-transform:uppercase;">31-60 DAYS</th>
                <th style="height: 35px;color: #fff;text-align: right;text-transform:uppercase;">61-90 DAYS</th>
                <th style="height: 35px;color: #fff;text-align: right;text-transform:uppercase;">>90 DAYS</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;width: 15%;">TOTAL</th>
            </tr>
            </thead>
            <tbody>
            @if($data)
                @foreach($data as $key => $values)
                    <tr>
                        <td style="vertical-align: middle;width: 10%;height: 35px;padding-left: 10px;">
                            {{ \App\Supplier::find($key)->display_name }}
                        </td>
                        <td style="vertical-align: middle;text-align: right;width: 14%;">{{ number_format(getAgingSummaryData($values, '1-30')) }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 14%;">{{ number_format(getAgingSummaryData($values, '31-60')) }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 14%;">{{ number_format(getAgingSummaryData($values, '61-90')) }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 14%;">{{ number_format(getAgingSummaryData($values, '91')) }}</td>
                        <td style="vertical-align: middle;text-align: right;width: 14%;padding-right: 10px;">{{ number_format(getAgingSummaryTotal($values)) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="1"
                        style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                        <strong>Total</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 15px;">
                        <strong>{{ number_format(getAgingSummaryIndividualTotal($data, '1-30')) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 15px;">
                        <strong>{{ number_format(getAgingSummaryIndividualTotal($data, '31-60')) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 15px;">
                        <strong>{{ number_format(getAgingSummaryIndividualTotal($data, '61-90')) }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 15px;">
                        <strong>{{ number_format(getAgingSummaryIndividualTotal($data, '91'))  }}</strong>
                    </td>
                    <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 15px;padding-right: 10px;">
                        <strong>{{ number_format(getAgingSummaryAllTotal($data))  }}</strong>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>