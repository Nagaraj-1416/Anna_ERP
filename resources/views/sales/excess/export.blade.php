<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Cash Excess ('.$route->name.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;">

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">CASH EXCESS</span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">AMOUNT: {{ number_format($excess->amount, 2) }}</span>
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
                        <b>Date: </b>{{ carbon($excess->date)->toDateString() }}
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

    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>

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
