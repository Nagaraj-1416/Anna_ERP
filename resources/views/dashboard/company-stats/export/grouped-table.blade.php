<div>
    <h4 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>{{ $heading }}</b></h4>
    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
        <thead>
        <tr style="background-color: #2f3d4a;">
            <th style="height: 35px;color: #fff;text-align: left;padding-left: 10px;">{{ $baseName }}</th>
            <th style="text-align: right;color: #fff;padding-right: 10px;">{{ $amountName }}</th>
        </tr>
        </thead>
        <tbody>
        @if($arrays)
            @foreach($arrays as $key => $reps)
                <tr>
                    <td style="padding-left: 10px; padding-top: 10px;">
                        <b>{{ array_get($reps, 'name') }}</b>
                    </td>
                    <td style="padding-top: 10px;text-align: right;padding-right: 10px;">
                        <b>{{ number_format(array_get($reps, 'total'), 2) }}</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                @foreach(array_get($reps, 'customers') as $customer)
                    <tr>
                        <td style="vertical-align: middle;width: 14%;height: 35px;padding-left: 20px;">
                            {{ array_get($customer, 'name') }}
                        </td>
                        <td style="vertical-align: middle;text-align: right;width: 14%;padding-right: 20px;">
                            {{ number_format(array_get($customer, 'total'), 2) }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;"><strong>Total</strong>
                </td>
                <td style="text-align: right;padding-right: 10px;border-top: 1px solid #D0D0D0; width: 20%;">
                    <strong>{{number_format($total) }}</strong>
                </td>
            </tr>
        @else
            <tr>
                <td colspan="2" style="text-align: left;border-top: 1px solid #D0D0D0;padding-left: 10px;">
                    No data to display...
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>