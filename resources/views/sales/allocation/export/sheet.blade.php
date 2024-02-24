<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Sales Sheet' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<div style="font-family: sans-serif;font-size: 12px;" id="salesSheet">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">{{ $allocationData['alCode'] }}</span><br/><br/>
                    <small style="color: #455a64;"><b>From: </b> {{ $allocation->from_date }} | <b>To: </b> {{ $allocation->to_date }}</small>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Account Transactions</span><br/><br/>
                    <small style="color: #455a64;"><b>Exported on: </b> {{ carbon()->now()->toDateTimeString() }}</small>
                </td>
            </tr>
        </table>
    </div>
    <hr style="margin-bottom: 10px;">
</div>
</body>
</html>