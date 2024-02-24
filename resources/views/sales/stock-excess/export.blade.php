<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Stock Excess ('.$route->name.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;">

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">STOCK EXCESS</span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">TOTAL ITEMS: {{ count($items) }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 15px;"></div>

    <!-- customer, company order information -->
    <div style="padding-top: 15px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <h4 style="font-weight: 600;font-family: sans-serif;"><b>{{ $company->name }}</b></h4>
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <div style="font-family: sans-serif;">
                        <h4 style="font-weight: 600;font-family: sans-serif;"><b>{{ $route->name }}</b></h4>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- order line items -->
    <div style="padding-top: 15px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 12px;">
                        <b>Date: </b>{{ $stock->date }}
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 12px;">
                        <b>Rep: </b>{{ $rep->name }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left"></td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 12px;">
                        <b>Allocation: </b>{{ $dailySale->code }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <div style="padding-top: 5px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;width: 50px;color: #fff;text-align: left;padding-left: 10px;">#</th>
                <th style="color: #fff;text-align: left;">Items & Description</th>
                <th style="text-align: center;color: #fff;width: 100px;">Quantity</th>
                <th style="text-align: right;color: #fff;width: 100px;">Rate</th>
                <th style="text-align: right;color: #fff;width: 100px;padding-right: 10px;">Amount</th>
            </tr>
            </thead>
            <tbody>
                @if(count($items))
                    @foreach($items as $itemKey => $item)
                        <tr>
                            <td style="height: 35px;width: 50px;text-align: left;vertical-align: middle;padding-left: 10px;">{{ ($itemKey+1) }}</td>
                            <td style="vertical-align: middle;">
                                {{ $item->product->name }}
                            </td>
                            <td style="vertical-align: middle;text-align: center;">{{ $item->qty }}</td>
                            <td style="vertical-align: middle;text-align: right;">{{ number_format($item->rate, 2) }}</td>
                            <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>

    <div>
        <table style="font-family: sans-serif;width: 100%;">
            <tr>
                <td style="width: 30%;padding-left: 10px;padding-top: 5px; vertical-align: top;"></td>
                <td style="width: 70%;text-align: right; vertical-align: top;">
                    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">
                                <strong>Total</strong></td>
                            <td style="text-align: right;padding-right: 7px;"><strong>{{ number_format($items->sum('amount'), 2) }}</strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- signatures panel -->
    <div style="margin-top: 50px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 11px;">
            <tr>
                <td style="width: 35%;">
                    <div>
                        <span style="border-top: 1px dotted; width: auto; padding: 10px">Authorized Person's Signature</span>
                    </div>
                </td>
                <td style="width: 35%;">
                    <div>
                        <span style="border-top: 1px dotted; width: auto; padding: 10px">Rep's Signature</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>
