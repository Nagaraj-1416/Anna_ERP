<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Inquiry Details' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="purchase_by_supplier">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Inquiry Details</span>
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
    @if(isset($inquiries))
        <div>
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                <thead>
                <tr style="background-color: #2f3d4a;">
                    <th style="height: 35px;color: #fff;text-align: left;width: 20%;padding-left: 10px;">CUSTOMER</th>
                    <th style="height: 35px;color: #fff;text-align: left;width: 20%;">INQUIRY#
                    </th>
                    <th style="text-align: left;color: #fff;width: 20%;">INQUIRY DATE</th>
                    <th style="text-align: left;color: #fff;width: 20%;">PREPARED BY</th>
                    <th style="text-align: left;color: #fff;width: 20%;">STATUS</th>
                </tr>
                </thead>
                <tbody>
                @if($inquiries)
                    @foreach($inquiries as $key => $inquiry)
                        <tr>
                            <td style="vertical-align: middle;width: 20%;height: 35px;padding-left: 10px;">
                                {{ $inquiry->customer->display_name ?? 'None' }}
                            </td>
                            <td style="vertical-align: middle;width: 20%;height: 35px;">
                                {{ $inquiry->code }}
                            </td>
                            <td style="vertical-align: middle;text-align: left;width: 20%;">{{ $inquiry->inquiry_date }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 20%;">{{ $inquiry->preparedBy->name }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 20%;">{{ $inquiry->status }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    @endif
</div>
</body>
</html>