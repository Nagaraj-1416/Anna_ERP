<?php
$order_data = array_get($purchase_data, 'order_data') ?? [];
$payment_data = array_get($purchase_data, 'payment_data') ?? [];
$credit_data = array_get($purchase_data, 'credit_data') ?? [];
?>
<table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
    <tr>
        <td style="width: 33%;font-family: sans-serif;vertical-align: top;">
            <div style="font-family: sans-serif; padding-top: 15px;">
                <h6 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>Orders Summary</b></h6>
                <table style="font-family: sans-serif;border-collapse: collapse; width: 60%;font-size: 12px;">
                    <tr>
                        <td style="text-align: left;">orders:</td>
                        <td style="width: 50%; text-align: right;">{{ array_get($order_data, 'count') }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Purchases:</td>
                        <td style="text-align: right;">{{ number_format(array_get($order_data, 'purchase'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Billed:</td>
                        <td style="text-align: right;">{{ number_format(array_get($order_data, 'billed'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Made:</td>
                        <td style="text-align: right;">{{ number_format(array_get($order_data, 'made'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Balance:</td>
                        <td style="text-align: right;">{{ number_format(array_get($order_data, 'balance'), 2) }}</td>
                    </tr>
                </table>
            </div>
        </td>
        <td style="width: 33%;font-family: sans-serif;vertical-align: top;">
            <div style="font-family: sans-serif; padding-top: 15px;">
                <h6 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>Payments Summary</b>
                </h6>
                <table style="font-family: sans-serif;border-collapse: collapse; width: 60%;font-size: 12px;">
                    <tr>
                        <td style="text-align: left;">Cash:</td>
                        <td style="width: 50%; text-align: right;">{{ number_format(array_get($payment_data, 'cash'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Cheque:</td>
                        <td style="text-align: right;">{{ number_format(array_get($payment_data, 'cheque'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Deposit:</td>
                        <td style="text-align: right;">{{ number_format(array_get($payment_data, 'direct_deposit') , 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Credit Card:</td>
                        <td style="text-align: right;">{{ number_format(array_get($payment_data, 'credit_card') , 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Received:</td>
                        <td style="text-align: right;">{{ number_format(array_get($payment_data, 'total') , 2) }}</td>
                    </tr>
                </table>
            </div>
        </td>
        <td style="width: 33%;font-family: sans-serif;vertical-align: top;">
            <div style="font-family: sans-serif; padding-top: 15px;">
                <h6 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>Credits Summary</b>
                </h6>
                <table style="font-family: sans-serif;border-collapse: collapse; width: 60%;font-size: 12px;">
                    <tr>
                        <td style="text-align: left;">Credits:</td>
                        <td style="width: 50%; text-align: right;">{{ number_format(array_get($credit_data, 'credits'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Refunded:</td>
                        <td style="text-align: right;">{{ number_format(array_get($credit_data, 'refunded'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Credited:</td>
                        <td style="text-align: right;">{{ number_format(array_get($credit_data, 'credited') , 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Remaining:</td>
                        <td style="text-align: right;">{{ number_format(array_get($credit_data, 'total') , 2) }}</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>