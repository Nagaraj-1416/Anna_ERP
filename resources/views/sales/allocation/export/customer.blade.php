<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Daily Sales Customers ('.$allocation->code.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 10px;" id="salesAllocation">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">SALES ALLOCATION</span><br />
                    <span style="font-weight: 700;color: #455a64;font-size: 12px;font-family: sans-serif;">
                        Associated Customers | As at {{ carbon()->now()->format('F j, Y') }}
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">{{ $allocation->code }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 5px;"></div>
    <div style="padding-top: 15px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 12px;">
                            <b>Allocation period:</b> {{ $allocation->from_date }} to {{ $allocation->to_date }}<br>
                        </span>
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;"></td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 12px;">
                            <b>Route:</b> {{ $allocation->route->name.' ('.$allocation->route->code.')' }}
                            <br>
                        </span>
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;"></td>
            </tr>
            <tr>
                <td style="width: 30%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 12px;">
                            <b>Vehicle:</b> {{ $allocation->vehicle->vehicle_no }} <br>
                        </span>
                    </div>
                </td>
                <td style="width: 30%;font-family: sans-serif;">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 12px;">
                            <b>Rep:</b> {{ $allocation->rep->name.' ('.$allocation->rep->code.')' }}
                            <br>
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <hr style="margin-bottom: 10px;">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;color: #fff;text-align: left;width: 15%;padding-left: 10px;">Code</th>
                <th style="text-align: left;color: #fff;width: 15%;">Name</th>
                <th style="text-align: left;color: #fff;width: 15%;">Phone</th>
                <th style="text-align: left;color: #fff;width: 15%;">Mobile</th>
                <th style="text-align: center;color: #fff;width: 15%;">Is visited?</th>
                <th style="text-align: left;color: #fff;width: 15%;padding-right: 10px;">Remarks</th>
            </tr>
            </thead>
            <tbody>
            @if($customers)
                @foreach($customers as $key => $customer)
                    <tr>
                        <td style="vertical-align: middle;width: 15%;height: 35px;padding-left: 10px;">{{ $customer->customer->code ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $customer->customer->display_name ?? 'None'}}</td>
                        <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $customer->customer->phone ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $customer->customer->mobile ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: center;width: 15%;">{{ $customer->is_visited ?? 'None' }}</td>
                        <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $customer->reason ?? 'None' }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>