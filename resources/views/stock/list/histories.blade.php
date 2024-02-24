<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Stock Histories ('.$product->code.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 10px;" id="salesAllocation">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 14px;font-family: sans-serif;">STOCK HISTORIES</span><br/>
                    <span style="font-weight: 700;color: #455a64;font-size: 10px;font-family: sans-serif;">
                        <b>Histories:</b> {{ count($histories) }} | As at {{ carbon()->now()->format('F j, Y') }}
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 14px;font-family: sans-serif;">{{ $product->code }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0;"></div>
    <div >
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 10px;">
                            <b>Available stock:</b> {{ $stock->available_stock }}<br>
                        </span>
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;"></td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 10px;">
                            <b>Stock available at:</b> {{ $stock->store->name }}
                            <br>
                        </span>
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;"></td>
            </tr>
        </table>
    </div>

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 10px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 20px;color: #fff;text-align: left;padding-left: 10px;width: 15%;">Date</th>
                <th style="text-align: left;color: #fff;width: 15%;">Type</th>
                <th style="text-align: center;color: #fff;width: 10%;">IN</th>
                <th style="text-align: center;color: #fff;width: 10%;">OUT</th>
                <th style="color: #fff;text-align: left;">Description</th>
            </tr>
            </thead>
            <tbody>
            @if($histories)
                @foreach($histories as $key => $history)
                    <tr>
                        <td style="vertical-align: middle;height: 20px;padding-left: 10px;"> {{ $history->trans_date }}</td>
                        <td style="vertical-align: middle;height: 20px;"> {{ $history->type }}</td>
                        <td style="vertical-align: middle;height: 20px;text-align: center;">
                            {{ $history->transaction == 'In' ? $history->quantity : '0' }}
                        </td>
                        <td style="vertical-align: middle;height: 20px;text-align: center;">
                            {{ $history->transaction == 'Out' ? $history->quantity : '0' }}
                        </td>
                        <td style="vertical-align: middle;padding-right: 10px;">
                            {{ $history->trans_description }}
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>