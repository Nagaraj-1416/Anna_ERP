<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | '.$priceBook->name }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 10px;" id="salesAllocation">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 14px;font-family: sans-serif;">Price Book</span><br/>
                    <span style="font-weight: 700;color: #455a64;font-size: 10px;font-family: sans-serif;">
                        <b>Prices:</b> {{ count($prices) }} | As at {{ carbon()->now()->format('F j, Y') }}
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 14px;font-family: sans-serif;">{{ $priceBook->code }}</span>
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
                            <b>Name:</b> {{ $priceBook->name }}<br>
                        </span>
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;"></td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 10px;">
                            <b>Category:</b> {{ $priceBook->category }}
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
                <th style="height: 20px;color: #fff;text-align: left;padding-left: 10px;width: 15%;">Products</th>
                <th style="text-align: left;color: #fff;width: 15%;">Price</th>
                <th style="text-align: center;color: #fff;width: 15%;">Qty Start Range</th>
                <th style="text-align: center;color: #fff;width: 10%;">Qty End Range</th>
            </tr>
            </thead>
            <tbody>
            @if($prices)
                @foreach($prices as $key => $price)
                    <tr>
                        <td style="vertical-align: middle;height: 20px;padding-left: 10px;text-align: left;"> {{ $price->product->name }}</td>
                        <td style="vertical-align: middle;height: 20px;text-align: left;"> {{ $price->price }}</td>
                        <td style="vertical-align: middle;height: 20px;text-align: center;"> {{ $price->range_start_from }}</td>
                        <td style="vertical-align: middle;padding-right: 10px;text-align: center;">
                            {{ $price->range_end_to }}
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