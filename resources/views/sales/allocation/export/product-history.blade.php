<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Daily Sales Stock History ('.$allocation->code.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 10px;" id="salesAllocation">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 14px;font-family: sans-serif;">SALES ALLOCATION</span><br />
                    <span style="font-weight: 700;color: #455a64;font-size: 10px;font-family: sans-serif;">
                        STOCK HISTORIES (IN / OUT) | As at {{ carbon()->now()->format('F j, Y') }}
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 14px;font-family: sans-serif;">{{ $allocation->code }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 10px;">
                            <b>Allocation period:</b> {{ $allocation->from_date }} to {{ $allocation->to_date }}<br>
                        </span>
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;"></td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 10px;">
                            <b>Route:</b> {{ $allocation->route->name.' ('.$allocation->route->code.')' }}
                        </span>
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;"></td>
            </tr>
            <tr>
                <td style="width: 30%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 10px;">
                            <b>Vehicle:</b> {{ $allocation->vehicle->vehicle_no }}
                        </span>
                    </div>
                </td>
                <td style="width: 30%;font-family: sans-serif;">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 10px;">
                            <b>Rep:</b> {{ $allocation->rep->name.' ('.$allocation->rep->code.')' }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 10px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="text-align: left;color: #fff;width: 15%;height: 20px; padding-left: 10px;">Date</th>
                <th style="text-align: left;color: #fff;width: 35%;">Description</th>
                <th style="text-align: center;color: #fff;width: 15%;">Quantity</th>
                <th style="text-align: center;color: #fff;width: 15%;padding-right: 10px;">Transaction</th>
            </tr>
            </thead>
            <tbody>
            @if($histories)
                @foreach($histories as $key => $data)
                    <tr>
                        <td style="padding-left: 10px; padding-top: 10px;" colspan="4">
                            <b>{{ ($data->first()->stock->product->name ?? 'None') . ' ('.$data->first()->stock->product->code ?? 'None'.')' }}</b>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <hr>
                        </td>
                    </tr>
                    @foreach($data as $key => $history)
                        <tr>
                            <td style="vertical-align: middle;text-align: left;width: 15%;height: 20px; padding-left: 10px;">{{ $history->trans_date ?? 'None'}}</td>
                            <td style="vertical-align: middle;text-align: left;width: 30%;">{{ $history->trans_description ?? 'None' }}</td>
                            <td style="vertical-align: middle;text-align: center;width: 15%;">{{ $history->quantity ?? 'None' }}</td>
                            <td style="vertical-align: middle;text-align: center;width: 15%;">{{ $history->transaction ?? 'None' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>