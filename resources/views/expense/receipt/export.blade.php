<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Expense Voucher' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="expenseVoucher">

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Expense Voucher</span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">#{{ $expense->expense_no ?? '' }}</span>
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
                        <!-- company address -->
                        <span style="font-family: sans-serif;color: #6c757d; font-size: 10px;">
                            {{ $companyAddress->street_one }},
                            @if($companyAddress->street_two)
                                {{ $companyAddress->street_two }},
                            @endif
                            @if($companyAddress->city)
                                {{ $companyAddress->city }},
                            @endif
                            @if($companyAddress->province)
                                <br/> {{ $companyAddress->province }},
                            @endif
                            @if($companyAddress->postal_code)
                                {{ $companyAddress->postal_code }},
                            @endif
                            @if($companyAddress->country)
                                <br/> {{ $companyAddress->country->name }}.
                            @endif
                        </span>
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
                        <b>Expense date: </b>#{{ $expense->expense_date }}
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 12px;">
                        <b>Payment Type: </b>{{ $expense->type->name }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>

    <div style="margin-top: 5px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr>
                <th colspan="4" style="font-weight: 600;font-family: sans-serif;font-size: 14px; text-align: left;">
                    Payment Details
                </th>
            </tr>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;width: 50px;color: #fff;text-align: left;padding-left: 10px;">#</th>
                <th style="color: #fff;width: 250px;text-align: left;">Payment date</th>
                <th style="color: #fff;text-align: left;">Payment mode</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;">Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($payments as $paymentKey => $payment)
                <tr>
                    <td style="height: 35px;width: 50px;text-align: left;vertical-align: middle;padding-left: 10px;">{{ ($paymentKey+1) }}</td>
                    <td style="vertical-align: middle;padding-right: 10px;">{{ $payment->payment_date }}</td>
                    <td style="vertical-align: middle;padding-right: 10px;">
                        {{ $payment->payment_mode }}
                        @if($payment->payment_mode == "Own Cheque")
                            <br />
                            <span><b>Cheque no: </b>{{ $payment->cheque_no or 'None' }}</span><br/>
                            <span><b>Cheque date: </b>{{ $payment->cheque_date or 'None' }}</span><br/>
                            <span><b>Written bank: </b>{{ $payment->bank->name or 'None' }}</span>
                        @endif
                    </td>
                    <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format($payment->payment, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div>
        <table style="font-family: sans-serif;width: 100%;">
            <tr>
                <td style="width: 30%;padding-top: 5px; vertical-align: top;"></td>
                <td style="width: 70%;text-align: right; vertical-align: top;">
                    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">
                                <strong>Total Paid</strong></td>
                            <td style="text-align: right;padding-right: 10px;"><strong>{{ number_format($expense->payments->sum('payment'), 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">
                                <strong>Remaining Payment</strong></td>
                            <td style="text-align: right;padding-right: 10px;"><strong>{{ number_format(($expense->amount - $expense->payments->sum('payment')), 2) }}</strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    @if($cheques)
        <div style="margin-top: 5px;">
            <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
                <thead>
                <tr>
                    <th colspan="2" style="font-weight: 600;font-family: sans-serif;font-size: 14px; text-align: left;">
                        Third Party Cheques
                    </th>
                </tr>
                <tr style="background-color: #2f3d4a;">
                    <th style="height: 35px;color: #fff;text-align: left;padding-left: 10px;">Cheque Details</th>
                    <th style="text-align: right;color: #fff;padding-right: 10px;">Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cheques as $chequeKey => $cheques)
                    @php
                    ['cheque_no' => $chequeNo] = chequeKeyToArray($chequeKey);
                    $chequeData = getChequeDataByNo($cheques->first());
                    @endphp
                    <tr>
                        <td style="height: 35px;text-align: left;vertical-align: middle;padding-left: 10px;padding-bottom: 10px">
                            <b>Cheque#</b><b>{{ $chequeNo }}</b><br />
                            <span>{{ $chequeData['formattedDate'] }}</span>,
                            <span>{{ $chequeData['bank'] }}</span> <br />
                            <span><b>Customer:</b>{{ $chequeData['customer'] }}</span>
                        </td>
                        <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format($chequeData['eachTotal'], 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td style="width: 82%;text-align: right;font-weight: 700;height: 25px;"><b>Third Party Cheques Total</b></td>
                    <td style="text-align: right;padding-right: 10px;"><b>{{ number_format($chequesAmount, 2) }}</b></td>
                </tr>
                </tbody>
            </table>
        </div>
    @endif

    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <!-- signatures panel -->
    <div style="margin-top: 50px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 11px;">
            <tr>
                <td style="width: 35%;">
                    <div>
                        <span style="border-top: 1px dotted; width: auto; padding: 10px">Authorized Signature</span>
                    </div>
                </td>
                <td style="width: 35%;">
                    <div>
                        <span style="border-top: 1px dotted; width: auto; padding: 10px">Receiver Signature</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>
