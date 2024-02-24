<table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
    <tr>
        <td style="width: 100%;font-family: sans-serif;" align="right" colspan="5">
            <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;"><strong>Account Transactions</strong></span>
        </td>
    </tr>
    <tr>
        <td style="width: 100%;font-family: sans-serif;" align="left" colspan="5">
            <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;"><strong>{{ $transData['accName'] }}</strong></span>
        </td>
    </tr>
    <tr>
        <td style="width: 100%;font-family: sans-serif;" align="left" colspan="5">
            <small style="color: #455a64;"><strong>From: </strong> {{ $transData['dateFrom'] }} | <strong>To: </strong> {{ $transData['dateTo'] }}</small>
        </td>
    </tr>
    <tr>
        <td style="width: 100%;font-family: sans-serif;" align="left" colspan="5">
            <small style="color: #455a64;"><strong>Exported on: </strong> {{ carbon()->now()->toDateTimeString() }}</small>
        </td>
    </tr>
</table>

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
            @if($transData['runningBalance']['intBalType'] == 'Debit')
                <b>{{ $transData['runningBalance']['intBal'] }}</b>
            @endif
        </td>
        <td style="vertical-align: middle;text-align: right;width: 18%;">
            @if($transData['runningBalance']['intBalType'] == 'Credit')
                <b>{{ $transData['runningBalance']['intBal'] }}</b>
            @endif
        </td>
        <td style="vertical-align: middle;text-align: right;width: 18%;padding-right: 5px;">
            <b>{{ $transData['runningBalance']['intBal'] }}</b>
        </td>
    </tr>
    @if($transData['runningBalance']['trans'])
        @foreach($transData['runningBalance']['trans'] as $tranKey => $tran)
            <tr>
                <td style="padding-bottom: 5px; padding-top: 5px;vertical-align: middle;width: 15%;height: 35px;padding-left: 10px;">{{ carbon($tran->date)->format('F j, Y') }}</td>
                <td style="padding-bottom: 5px; padding-top: 5px;vertical-align: middle;text-align: left;">{{ $tran->transaction->auto_narration or 'None' }}</td>
                <td style="padding-bottom: 5px; padding-top: 5px;vertical-align: middle;text-align: right;width: 18%;">{{ $tran->type == 'Debit' ? $tran->amount : '' }}</td>
                <td style="padding-bottom: 5px; padding-top: 5px;vertical-align: middle;text-align: right;width: 18%;">{{ $tran->type == 'Credit' ? $tran->amount : '' }}</td>
                <td style="padding-bottom: 5px; padding-top: 5px;vertical-align: middle;text-align: right;width: 18%;padding-right: 5px;">{{ $tran->balance }}</td>
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
            <b>{{ $transData['runningBalance']['debitBal'] }}</b>
        </td>
        <td style="vertical-align: middle;text-align: right;width: 18%;">
            <b>{{ $transData['runningBalance']['creditBal'] }}</b>
        </td>
        <td style="vertical-align: middle;text-align: right;width: 18%;padding-right: 5px;">
            <b>{{ $transData['runningBalance']['endBal'] }}</b>
        </td>
    </tr>
    <tr>
        <td style="vertical-align: middle;height: 35px;padding-left: 10px;" colspan="4">
            <b>Balance Change</b><br />
            <small>Difference between starting and ending balances</small>
        </td>
        <td style="vertical-align: middle;text-align: right;width: 18%;padding-right: 5px;">
            <b>{{ abs($transData['runningBalance']['intBal'] - $transData['runningBalance']['endBal']) }}</b>
        </td>
    </tr>
    </tfoot>
</table>