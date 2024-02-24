<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Customers' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="salesAllocation">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Customer</span><br/>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Total Customer {{ $customers->count() }}</span>
                </td>
            </tr>
        </table>
    </div>

    <hr style="margin-bottom: 10px;">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;color: #fff;text-align: left;width: 10%;padding-left: 10px;">Code</th>
                <th style="text-align: left;color: #fff;width: 15%;">Display Name</th>
                <th style="text-align: left;color: #fff;width: 15%;">Full Name</th>
                <th style="text-align: left;color: #fff;width: 15%;">Route</th>
                <th style="text-align: left;color: #fff;width: 15%;">Location</th>
                <th style="text-align: left;color: #fff;width: 10%;">Phone</th>
                <th style="text-align: left;color: #fff;padding-right: 10px;">Address</th>
            </tr>
            </thead>
            <tbody>
            @if($customers)
                @foreach($customers as $key => $customer)
                    <?php
                    $address = $customer->addresses->first();
                    ?>
                    <tr>
                        <td style="vertical-align: middle;width: 10%;height: 35px;padding-left: 10px;">{{ $customer->code ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $customer->display_name ?? 'None'}}</td>
                        <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $customer->full_name ?? 'None'}}</td>
                        <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $customer->route->name ?? 'None'}}</td>
                        <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $customer->location->name ?? 'None'}}</td>
                        <td style="vertical-align: middle;text-align: left;width: 10%;">{{ $customer->phone ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: left;padding-right: 10px;">{{addressExport($address)}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
{{--{{ dd(1) }}--}}