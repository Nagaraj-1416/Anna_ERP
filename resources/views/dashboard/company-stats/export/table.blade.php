<div>
    <h4 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>{{ $heading }}</b></h4>
    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
        <thead>
        <tr style="background-color: #2f3d4a;">
            <th style="height: 35px;color: #fff;text-align: left;padding-left: 10px;">{{ $baseName }}</th>
            @if(isset($products))
                <th style="height: 35px;color: #fff;text-align: center;padding-left: 10px;">PRODUCT QTY</th>
            @endif
            <th style="text-align: right;color: #fff;padding-right: 10px;">{{ $amountName }}</th>
        </tr>
        </thead>
        <tbody>
        @if($arrays)
            @foreach($arrays as $key => $array)
                <tr>
                    <td style="vertical-align: middle;padding-left: 10px;">
                        {{ array_get($array, $get) }}
                    </td>
                    @if(isset($products))
                        <td style="vertical-align: middle;text-align: center;">
                            {{ array_sum(array_pluck(array_pluck(array_get($array, $products), 'pivot') , 'quantity')) }}
                        </td>
                    @endif
                    <td style="vertical-align: middle;text-align: right;padding-right: 10px;">
                        @if(isset($balance))
                            {{ number_format(array_sum(array_pluck(array_get($array, 'orders', []), 'total')) - array_sum(array_pluck(array_get($array, 'payments', []), 'payment'))) }}
                        @else
                            {{ number_format(array_sum(array_pluck(array_get($array, $relation, []), $pluck))) }}
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="{{ isset($products) ? 2 : '' }}"
                    style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;"><strong>Total</strong>
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