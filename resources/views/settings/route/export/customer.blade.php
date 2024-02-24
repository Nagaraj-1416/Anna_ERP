<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Route Customers ('.$route->code.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="salesAllocation">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Route & Customers</span><br/>
                    <span style="font-weight: 700;color: #455a64;font-size: 12px;font-family: sans-serif;">
                        {{ $route->name }}
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">{{ $route->code }}</span>
                </td>
            </tr>
        </table>
    </div>

    <hr style="margin-bottom: 10px;">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="text-align: left;color: #fff;">Display name</th>
                <th style="text-align: left;color: #fff;width: 25%;">Owner name</th>
                <th style="text-align: left;color: #fff;width: 15%;padding-right: 10px;">Location</th>
                <th style="text-align: left;color: #fff;width: 15%;padding-right: 10px;">Mobile</th>
            </tr>
            </thead>
            <tbody>
            @if($customers)
                @foreach($customers as $key => $customer)
                    <tr>
                        <td style="vertical-align: middle;height: 35px;padding-left: 10px;">{{ $customer->display_name ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: left;">{{ $customer->full_name ?? 'None'}}</td>
                        <td style="vertical-align: middle;text-align: left;padding-right: 10px;">{{ $customer->location->name ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: left;">{{ $customer->mobile ?? 'None'}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>