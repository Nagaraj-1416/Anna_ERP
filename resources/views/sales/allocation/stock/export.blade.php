<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Stock Allocation ('.$route->name.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;">

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">STOCK ALLOCATION</span>
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
                        <b>Store: </b>#{{ $store->name }}
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
                        <b>Sales van: </b>{{ $salesVan->name }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <!-- order payment and balance summary -->
    <div style="padding-top: 5px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;width: 50px;color: #fff;text-align: left;padding-left: 10px;">#</th>
                <th style="color: #fff;text-align: left;">Items</th>
                <th style="text-align: center;color: #fff;width: 100px;">Available Qty</th>
                <th style="text-align: center;color: #fff;width: 100px;">Default Qty</th>
                <th style="text-align: center;color: #fff;width: 100px;padding-right: 10px;">Required Qty</th>
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
                            <td style="vertical-align: middle;text-align: center;">{{ $item->available_qty or 'None' }}</td>
                            <td style="vertical-align: middle;text-align: center;">{{ $item->default_qty or 'None' }}</td>
                            <td style="vertical-align: middle;text-align: center;">{{ $item->required_qty or 'None' }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>

    <!-- signatures panel -->
    <div style="margin-top: 50px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 11px;">
            <tr>
                <td style="width: 35%;">
                    <div>
                        <span style="border-top: 1px dotted; width: auto; padding: 10px">SK's Signature</span>
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
