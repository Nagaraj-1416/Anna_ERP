<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Account Transactions' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="transactions">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">{{ $accName }}</span><br/><br/>
                    <small style="color: #455a64;"><b>From: </b> {{ $dateFrom }} | <b>To: </b> {{ $dateTo }}</small>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">Account Transactions</span><br/><br/>
                    <small style="color: #455a64;"><b>Exported on: </b> {{ carbon()->now()->toDateTimeString() }}</small>
                </td>
            </tr>
        </table>
    </div>

    <hr style="margin-bottom: 10px;">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
                <tr style="background-color: #2f3d4a;">
                    <th style="height: 35px;color: #fff;text-align: left;width: 15%;padding-left: 10px;">DATE</th>
                    <th style="text-align: left;color: #fff;">DESCRIPTION</th>
                    <th style="text-align: right;color: #fff;width: 18%;">DEBIT</th>
                    <th style="text-align: right;color: #fff;width: 18%;">CREDIT</th>
                    <th style="text-align: right;color: #fff;width: 18%; padding-right: 5px;">BALANCE</th>
                </tr>
            </thead>
            <tbody style="padding-left: 35px !important;">
                <tr>
                    <td style="vertical-align: middle;height: 35px;padding-left: 10px;" colspan="2">
                        <b>Starting Balance</b>
                    </td>
                    <td style="vertical-align: middle;text-align: right;width: 18%;">
                        @if($runningBalance['intBalType'] == 'Debit')
                            <b>{{ number_format($runningBalance['intBal'], 2) }}</b>
                        @endif
                    </td>
                    <td style="vertical-align: middle;text-align: right;width: 18%;">
                        @if($runningBalance['intBalType'] == 'Credit')
                            <b>{{ number_format($runningBalance['intBal'], 2) }}</b>
                        @endif
                    </td>
                    <td style="vertical-align: middle;text-align: right;width: 18%;padding-right: 5px;">
                        <b>{{ number_format($runningBalance['intBal'], 2) }}</b>
                    </td>
                </tr>
                @if($runningBalance['trans'])
                    @foreach($runningBalance['trans'] as $tranKey => $tran)
                        <tr>
                            <td style="padding-bottom: 5px; padding-top: 5px;vertical-align: middle;width: 15%;height: 35px;padding-left: 10px;">{{ carbon($tran->date)->format('F j, Y') }}</td>
                            <td style="padding-bottom: 5px; padding-top: 5px;vertical-align: middle;text-align: left;">{{ $tran->transaction->auto_narration or 'None' }}</td>
                            <td style="padding-bottom: 5px; padding-top: 5px;vertical-align: middle;text-align: right;width: 18%;">{{ $tran->type == 'Debit' ? number_format($tran->amount, 2) : '' }}</td>
                            <td style="padding-bottom: 5px; padding-top: 5px;vertical-align: middle;text-align: right;width: 18%;">{{ $tran->type == 'Credit' ? number_format($tran->amount, 2) : '' }}</td>
                            <td style="padding-bottom: 5px; padding-top: 5px;vertical-align: middle;text-align: right;width: 18%;padding-right: 5px;">{{ number_format($tran->balance, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No Transactions Founds...</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td style="vertical-align: middle;height: 35px;padding-left: 10px;" colspan="2">
                        <b>Totals and Ending Balance</b>
                    </td>
                    <td style="vertical-align: middle;text-align: right;width: 18%;">
                        <b>{{ number_format($runningBalance['debitBal'], 2) }}</b>
                    </td>
                    <td style="vertical-align: middle;text-align: right;width: 18%;">
                        <b>{{ number_format($runningBalance['creditBal'], 2) }}</b>
                    </td>
                    <td style="vertical-align: middle;text-align: right;width: 18%;padding-right: 5px;">
                        <b>{{ number_format($runningBalance['endBal'], 2) }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: middle;height: 35px;padding-left: 10px;" colspan="4">
                        <b>Balance Change</b><br />
                        <small>Difference between starting and ending balances</small>
                    </td>
                    <td style="vertical-align: middle;text-align: right;width: 18%;padding-right: 5px;">
                        <b>{{ number_format(abs($runningBalance['intBal'] - $runningBalance['endBal']), 2) }}</b>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
</body>
</html>
{{--{{ dd(1) }}--}}