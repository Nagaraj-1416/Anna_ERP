<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME').' | Transaction ('.$trans->code.')' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="Transaction">

    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="left">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">TRANSACTION</span>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">#{{ $trans->code ?? '' }}</span>
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
                    <table style="width: 300px">
                        <tr style="color: #455a64;font-family: sans-serif;font-size: 12px;">
                            <td> Transaction date:</td>
                            <td class="text-right">{{ $trans->date }}</td>
                        </tr>
                        <tr style="color: #455a64;font-family: sans-serif;font-size: 12px;">
                            <td>Category: </td>
                            <td class="text-right">{{ $trans->category }}</td>
                        </tr>
                        <tr style="color: #455a64;font-family: sans-serif;font-size: 12px;">
                            <td> Type: </td>
                            <td class="text-right">{{ $trans->type }}</td>
                        </tr>
                        <tr style="color: #455a64;font-family: sans-serif;font-size: 12px;">
                            <td> Business type: </td>
                            <td class="text-right">{{ $trans->businessType->name or 'None' }}</td>
                        </tr>
                        <tr style="color: #455a64;font-family: sans-serif;font-size: 12px;">
                            <td> Transaction type: </td>
                            <td class="text-right">{{ $trans->txType->name or 'None' }}</td>
                        </tr>
                        <tr style="color: #455a64;font-family: sans-serif;font-size: 12px;">
                            <td> Amount: </td>
                            <td class="text-right">{{ number_format($trans->amount, 2) }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;font-family: sans-serif;" align="right" valign="top">
                    <div style="font-family: sans-serif; vertical-align: top">
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
    <div style="width: 100%; margin-top: 10px;"></div>
    <!-- order payment and balance summary -->
    <div style="padding-top: 5px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
            <thead>
            <tr style="background-color: #2f3d4a;">
                <th style="height: 35px;width: 50px;color: #fff;text-align: left;padding-left: 10px;">#</th>
                <th style="color: #fff;text-align: left;width: 250px;">Account</th>
                <th style="text-align: right;color: #fff;">Debit</th>
                <th style="text-align: right;color: #fff; padding-right: 10px">Credit</th>
            </tr>
            </thead>
            <tbody>
            @if(count($records))
                @foreach($records as $itemKey => $record)
                    <tr>
                        <td style="height: 35px;width: 50px;text-align: left;vertical-align: middle;padding-left: 10px;">{{ ($itemKey+1) }}</td>
                        <td style="vertical-align: middle;">
                            {{ $record->account->name }}<br/>
                        </td>
                        <td style="vertical-align: middle;text-align: right;">{{ number_format(($record->type == 'Debit') ? $record->amount : 0.00, 2) }}</td>
                        <td style="vertical-align: middle;text-align: right;padding-right: 10px;">{{ number_format(($record->type == 'Credit') ? $record->amount : 0.00, 2) }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>

    <!-- terms and notes panel -->
    <div style="margin-top: 15px;">
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="font-size: 12px;">
                    Narration<br/>
                    <span style="font-size: 10px;color: #818181">{{ $trans->manual_narration ? $trans->manual_narration :  $trans->auto_narration  }}</span>
                </td>
            </tr>
            <tr>
                <td style="font-size: 12px; padding-top: 15px;">
                    Notes<br/>
                    <span style="font-size: 10px;color: #818181">{{ $trans->notes }}</span>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
