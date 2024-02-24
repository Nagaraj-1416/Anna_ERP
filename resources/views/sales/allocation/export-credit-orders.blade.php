<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Credit Orders ('.$route->name.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;">

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">CREDIT ORDERS</span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">TOTAL ITEMS: {{ count($creditOrders) }}</span>
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
                        <b>Allocation: </b>{{ $allocation->code }}
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
                        <b>Date: </b>{{ $allocation->from_date }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <div style="padding-top: 5px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;color: #fff;text-align: left;padding-left: 10px">Customer</th>
                <th style="color: #fff;text-align: left;padding-right: 10px;">Order#</th>
                <th style="text-align: right;color: #fff;width: 100px;">Amount</th>
                <th style="text-align: right;color: #fff;width: 100px;">Paid</th>
                <th style="text-align: right;color: #fff;width: 100px;padding-right: 10px;">Balance</th>
            </tr>
            </thead>
            <tbody>
                @if(count($creditOrders))
                    @foreach($creditOrders as $creditOrderKey => $creditOrder)
                        <tr>
                            <td style="height: 35px;vertical-align: top;text-align: left;padding-left: 10px">
                                {{ $creditOrder->customer->display_name }}
                            </td>
                            <td style="vertical-align: top;text-align: left;">
                                {{ $creditOrder->ref }} <br />
                                {{ $creditOrder->order_date }}
                            </td>
                            <td style="vertical-align: top;text-align: right;">{{ number_format($creditOrder->total, 2) }}</td>
                            <td style="vertical-align: top;text-align: right;">{{ number_format($creditOrder->paid, 2) }}</td>
                            <td style="vertical-align: top;text-align: right;padding-right: 10px;">{{ number_format($creditOrder->balance, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr style="padding-top: 10px;">
                        <td colspan="2"
                            style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                            <strong>Total</strong>
                        </td>
                        <td style="text-align: right;border-top: 1px solid #D0D0D0;">
                            <strong>{{ number_format($totalSales, 2) }}</strong>
                        </td>
                        <td style="text-align: right;border-top: 1px solid #D0D0D0;">
                            <strong>{{ number_format($totalPaid, 2) }}</strong>
                        </td>
                        <td style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 10px;">
                            <strong>{{ number_format($totalBalance, 2) }}</strong>
                        </td>
                    </tr>
                @endif
            </tbody>
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
