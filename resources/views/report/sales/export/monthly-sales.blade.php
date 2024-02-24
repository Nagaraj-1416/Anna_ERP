<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Monthly Sales' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="sales_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Monthly Sales</span>
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
    <div>
        <div style="margin-top: 10px;"></div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;color: #fff;text-align: left;width: 15%;padding-left: 10px;">CUSTOMER</th>
                @foreach($dates as $date)
                    <th style="height: 35px;color: #fff;text-align: right;text-transform:uppercase;">{{ $date }}</th>
                @endforeach
                <th style="text-align: right;color: #fff;padding-right: 10px;width: 14%;">TOTAL</th>
            </tr>
            </thead>
            <tbody>
            @if($data)
                @foreach($data as $key => $values)
                    <tr>
                        <td style="vertical-align: middle;width: 10%;height: 35px;padding-left: 10px;">
                            {{ \App\Customer::find($key)->display_name }}
                        </td>
                        @foreach($values as $value)
                            <td style="vertical-align: middle;text-align: right;width: 14%;">{{ number_format($value) }}</td>
                        @endforeach
                        <td style="vertical-align: middle;text-align: right;width: 14%;padding-right: 10px;">{{ number_format(array_sum($values)) }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>