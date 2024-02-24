<?php
$receipts_data = array_get($expense_data, 'receipts_data') ?? [];
$payment = array_get($expense_data, 'payment') ?? [];
$reports_data = array_get($expense_data, 'reports_data') ?? [];
?>
<table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
    <tr>
        <td style="width: 33%;font-family: sans-serif;vertical-align: top;">
            <div style="font-family: sans-serif; padding-top: 15px;">
                <h6 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>Receipts Summary</b></h6>
                <table style="font-family: sans-serif;border-collapse: collapse; width: 80%;font-size: 12px;">
                    <tr>
                        <td style="text-align: left;">No of receipts:</td>
                        <td style="width: 50%; text-align: right;">{{ array_get($receipts_data, 'count') }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Unreported:</td>
                        <td style="text-align: right;">{{ number_format(array_get($receipts_data, 'unreported'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Unsubmitted:</td>
                        <td style="text-align: right;">{{ number_format(array_get($receipts_data, 'unsubmitted'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Submitted:</td>
                        <td style="text-align: right;">{{ number_format(array_get($receipts_data, 'submitted'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Approved:</td>
                        <td style="text-align: right;">{{ number_format(array_get($receipts_data, 'approved'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Rejected:</td>
                        <td style="text-align: right;">{{ number_format(array_get($receipts_data, 'rejected'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Reimbursed:</td>
                        <td style="text-align: right;">{{ number_format(array_get($receipts_data, 'reimbursed'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Total:</td>
                        <td style="text-align: right;">{{ number_format(array_get($receipts_data, 'total'), 2) }}</td>
                    </tr>
                </table>
            </div>
        </td>
        <td style="width: 33%;font-family: sans-serif;vertical-align: top;">
            <div style="font-family: sans-serif; padding-top: 15px;">
                <h6 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>Reports Summary</b>
                </h6>
                <table style="font-family: sans-serif;border-collapse: collapse; width: 80%;font-size: 12px;">
                    <tr>
                        <td style="text-align: left;">No of reports:</td>
                        <td style="width: 50%; text-align: right;">{{ array_get($reports_data, 'count') }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Drafted:</td>
                        <td style="text-align: right;">{{ number_format(array_get($reports_data, 'drafted'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Submitted:</td>
                        <td style="text-align: right;">{{ number_format(array_get($reports_data, 'submitted') , 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Approved:</td>
                        <td style="text-align: right;">{{ number_format(array_get($reports_data, 'approved') , 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Rejected:</td>
                        <td style="text-align: right;">{{ number_format(array_get($reports_data, 'rejected') , 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Partially Reimbursed:</td>
                        <td style="text-align: right;">{{ number_format(array_get($reports_data, 'partially_reimbursed') , 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Reimbursed:</td>
                        <td style="text-align: right;">{{ number_format(array_get($reports_data, 'reimbursed') , 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Total:</td>
                        <td style="text-align: right;">{{ number_format(array_get($reports_data, 'total') , 2) }}</td>
                    </tr>
                </table>
            </div>
        </td>
        <td style="width: 33%;font-family: sans-serif;vertical-align: top;">
            <div style="font-family: sans-serif; padding-top: 15px;">
                <h6 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>Payments Summary</b>
                </h6>
                <table style="font-family: sans-serif;border-collapse: collapse; width: 80%;font-size: 12px;">
                    <tr>
                        <td style="text-align: left;">Cash:</td>
                        <td style="width: 50%; text-align: right;">{{ number_format(array_get($payment, 'cash'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Cheque:</td>
                        <td style="text-align: right;">{{ number_format(array_get($payment, 'cheque'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Deposit:</td>
                        <td style="text-align: right;">{{ number_format(array_get($payment, 'deposit') , 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Credit Card:</td>
                        <td style="text-align: right;">{{ number_format(array_get($payment, 'credit_card') , 2) }}</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>