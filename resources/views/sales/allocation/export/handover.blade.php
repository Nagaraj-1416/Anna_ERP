<div>
    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 50%;font-family: sans-serif;" align="left">
                <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">SALES HANDOVER</span>
            </td>
            <td style="width: 50%;font-family: sans-serif;" align="right">
                <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">#{{ $handover->code ?? '' }}</span>
            </td>
        </tr>
    </table>
</div>

<div style="border-top: 1px solid #D0D0D0; margin-top: 15px;"></div>
<div style="padding-top: 15px;">
    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 50%;font-family: sans-serif;" align="left">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif;color: #6c757d; font-size: 10px;">
                            <b>Date:</b> {{ $handover->date }} <br>
                            <b>No of cheques:</b> {{ $handover->cheque_count ?? 'None' }} <br>
                        </span>
                </div>
            </td>
        </tr>
    </table>
</div>

<table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
    <tr>
        <td style="width: 35%;font-family: sans-serif;vertical-align: top;">
            <div style="font-family: sans-serif; padding-top: 15px;">
                <h6 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>Collection from today's
                        sales</b></h6>
                <table style="font-family: sans-serif;border-collapse: collapse; width: 80%;font-size: 12px;">
                    <tr>
                        <td style="text-align: left;">Cash:</td>
                        <td style="width: 50%; text-align: right;">{{ number_format($handover->cash_sales, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Cheque:</td>
                        <td style="text-align: right;">{{ number_format($handover->cheque_sales, 2)  }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Deposit:</td>
                        <td style="text-align: right;">{{ number_format($handover->deposit_sales, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Card:</td>
                        <td style="text-align: right;">{{ number_format($handover->card_sales, 2)  }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Credit:</td>
                        <td style="text-align: right;">{{ number_format($handover->credit_sales, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Total:</td>
                        <td style="text-align: right;">{{ number_format($handover->sales, 2) }}</td>
                    </tr>
                </table>
            </div>
        </td>

        <td style="width: 35%;font-family: sans-serif;vertical-align: top;">
            <div style="font-family: sans-serif; padding-top: 15px;">
                <h6 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>Collection from old
                        sales</b></h6>
                <table style="font-family: sans-serif;border-collapse: collapse; width: 80%;font-size: 12px;">
                    <tr>
                        <td style="text-align: left;">Cash:</td>
                        <td style="width: 50%; text-align: right;">{{ number_format($handover->old_cash_sales, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Cheque:</td>
                        <td style="text-align: right;">{{ number_format($handover->old_cheque_sales, 2)  }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Deposit:</td>
                        <td style="text-align: right;">{{ number_format($handover->old_deposit_sales, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Card:</td>
                        <td style="text-align: right;">{{ number_format($handover->old_card_sales, 2)  }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Credit:</td>
                        <td style="text-align: right;">{{ number_format($handover->old_credit_sales, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Total:</td>
                        <td style="text-align: right;">{{ number_format($handover->old_sales, 2) }}</td>
                    </tr>
                </table>
            </div>
        </td>

        <td style="width: 30%;font-family: sans-serif;vertical-align: top;">
            <div style="font-family: sans-serif; padding-top: 15px;">
                <h6 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>Summary</b></h6>
                <table style="font-family: sans-serif;border-collapse: collapse; width: 80%;font-size: 12px;">
                    <tr>
                        <td style="text-align: left;">Total collection:</td>
                        <td style="width: 50%; text-align: right;">{{ number_format($handover->total_collect, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Total expense:</td>
                        <td style="text-align: right;">{{ number_format($handover->total_expense, 2)  }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Allowance:</td>
                        <td style="text-align: right;">{{ number_format($handover->allowance, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Sales commission:</td>
                        <td style="text-align: right;">{{ number_format($handover->sales_commission, 2)  }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">Shortage:</td>
                        <td style="text-align: right;">{{ number_format($handover->shortage, 2) }}</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="3">
            <hr>
        </td>
    </tr>

    <tr>
        <td style="width: 35%;font-family: sans-serif;vertical-align: top;">
            <div style="font-family: sans-serif; padding-top: 15px;">
                <h6 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>Cash Breakdowns</b></h6>
                <table style="font-family: sans-serif;border-collapse: collapse; width: 80%;font-size: 12px;">
                    <thead>
                    <tr>
                        <th style="height: 35px;text-align: left;padding-left: 10px;">Rupee</th>
                        <th style="height: 35px;text-align: left;">Count</th>
                        <th style="height: 35px;text-align: right;padding-right: 10px;">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($handover->breakdowns as $breakdown)
                        <tr>
                            <td style="vertical-align: middle;padding-left: 10px;">
                                {{ $breakdown->rupee_type }}
                            </td>
                            <td style="vertical-align: middle;">
                                {{ $breakdown->count }}
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding-right: 10px;">
                                {{ number_format(($breakdown->rupee_type * $breakdown->count), 2) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </td>
    </tr>
</table>


<div style="border-top: 1px solid #D0D0D0; margin-top: 15px;"></div>
<div style="padding-top: 15px;">
    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 30%;font-family: sans-serif;vertical-align: top;">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 12px;">
                            <b>Rep:</b> {{ $handover->rep->name ?? 'None' }} <br>
                        </span>
                </div>
            </td>
            <td style="width: 30%;font-family: sans-serif;vertical-align: top;">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 12px;">
                            <b>Prepared by:</b> {{ $handover->preparedBy->name ?? 'None' }} <br>
                        </span>
                </div>
            </td>
            <td style="font-family: sans-serif;vertical-align: top;">
                <div style="font-family: sans-serif;">
                        <span style="font-family: sans-serif; font-size: 12px;">
                            <b>Notes:</b> <br>
                            {{ $handover->notes ?? 'None' }}
                        </span>
                </div>
            </td>
        </tr>
    </table>
</div>