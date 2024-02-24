<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Expense Report ('.$report->report_no.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="expenseReport">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">EXPENSE REPORT</span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">#{{ $report->report_no ?? '' }}</span>
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
                        <h4 style="font-weight: 600;font-family: sans-serif;"><b>{{ $company->name or '' }}</b></h4>
                        <!-- company address -->
                        <span style="font-family: sans-serif;color: #6c757d; font-size: 10px;">
                            {{ $companyAddress->street_one or ''}},
                            @if($companyAddress && $companyAddress->street_two)
                                {{ $companyAddress->street_two  or ''}},
                            @endif
                            @if($companyAddress && $companyAddress->city)
                                {{ $companyAddress->city }},
                            @endif
                            @if($companyAddress && $companyAddress->province)
                                <br/> {{ $companyAddress->province }},
                            @endif
                            @if($companyAddress && $companyAddress->postal_code)
                                {{ $companyAddress->postal_code }},
                            @endif
                            @if($companyAddress && $companyAddress->country)
                                <br/> {{ $companyAddress->country->name }}.
                            @endif
                        </span>
                    </div>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <div style="font-family: sans-serif;">
                        @php
                            $staff = $report->preparedBy ? $report->preparedBy->staffs->first() : null;
                            $preparedUserAddress = $staff ? $staff->addresses->first() : null;
                        @endphp
                        <h4 style="font-weight: 600;font-family: sans-serif;"><b>{{ $staff ? $staff->full_name : 'None' }}</b></h4>
                        <!-- company address -->
                        <span style="font-family: sans-serif;color: #6c757d; font-size: 10px;">
                            {{ $preparedUserAddress->street_one or ''}},
                            @if($preparedUserAddress && $preparedUserAddress->street_two)
                                {{ $preparedUserAddress->street_two }},
                            @endif
                            @if($preparedUserAddress && $preparedUserAddress->city)
                                {{ $preparedUserAddress->city }},
                            @endif
                            @if($preparedUserAddress && $preparedUserAddress->province)
                                <br/> {{ $preparedUserAddress->province }},
                            @endif
                            @if($preparedUserAddress && $preparedUserAddress->postal_code)
                                {{ $preparedUserAddress->postal_code }},
                            @endif
                            @if($preparedUserAddress && $preparedUserAddress->country)
                                <br/> {{ $preparedUserAddress->country->name }}.
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
                        <b>{{ $report->title }} </b>
                    </span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 12px;">
                        <b>Amount to be Reimbursed : </b>LKR {{ number_format(reportReimbursementAmount($report), 2) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <div style="padding-top: 15px;">
        <div style="border-top: 1px solid rgba(207, 207, 207, 0.4); margin-bottom: 10px;"></div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 11px">
            <tr>
                <td style="width: 25%;">
                    <span style="font-weight: 700;color: #455a64;">
                        <b> Submitted By </b>
                        <br> {{ $report->submittedBy->name or '' }}
                    </span>
                </td>
                <td style="width: 25%;">
                    <span style="font-weight: 700;color: #455a64;">
                        <b> Report To </b>
                        <br> {{ $report->approvedBy->name or '' }}
                    </span>
                </td>
                <td style="width: 25%;">
                    <span style="font-weight: 700;color: #455a64;">
                        <b> Submitted On </b>
                        <br> {{ $report->submitted_on or '' }}
                    </span>
                </td>
                <td style="width: 25%;">
                    <span style="font-weight: 700;color: #455a64;">
                        <b> Report Period </b>
                        <br> {{ $report->report_from or '' }} - {{ $report->report_to or '' }}
                    </span>
                </td>
            </tr>
        </table>
        <div style="border-top: 1px solid rgba(207, 207, 207, 0.4); margin-top: 10px;"></div>
    </div>


    <!-- order payment and balance summary -->
    <div style="padding-top: 5px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;width: 50px;color: #fff;text-align: left;padding-left: 10px;">#</th>
                <th style="color: #fff;text-align: left;width: 150px;">Expense No</th>
                <th style="text-align: left; color: #fff;">Expense date</th>
                <th style="text-align: left;color: #fff;">Category Name</th>
                <th style="text-align: right;color: #fff;padding-right: 10px;">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($report->expenses as $itemKey => $expense)
                <tr>
                    <td style="height: 35px;width: 50px;text-align: left;vertical-align: middle;padding-left: 10px;">{{ ($itemKey+1) }}</td>
                    <td style="vertical-align: middle;">{{ $expense->expense_no }}</td>
                    <td style="vertical-align: middle;">{{ $expense->expense_date }}</td>
                    <td style="vertical-align: middle;">{{ $expense->category->name or '' }}</td>
                    <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format($expense->amount, 2) }}</td>
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
                        @php
                            $totalAmount = $report->expenses->sum('amount');
                            $reimbursableAmount = reportReimbursementAmount($report);
                        @endphp
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">
                                <strong>Total Expense Amount</strong></td>
                            <td style="text-align: right;padding-right: 10px;"><strong>{{ number_format($totalAmount, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">
                                <strong>Non Reimbursable Amount</strong></td>
                            <td style="text-align: right;padding-right: 10px;"><strong>(-){{ number_format(($totalAmount - $reimbursableAmount), 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td style="width: 75%;text-align: right;font-weight: 700;height: 25px;">
                                <strong>Total Reimbursable Amount </strong></td>
                            <td style="text-align: right;padding-right: 10px;"><strong>{{ number_format($reimbursableAmount, 2) }}</strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- terms and notes panel -->
    <div style="margin-top: 15px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="font-size: 12px; padding-top: 15px;">
                    Notes<br/>
                    <span style="font-size: 10px;color: #818181">{{ $report->notes }}</span>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
