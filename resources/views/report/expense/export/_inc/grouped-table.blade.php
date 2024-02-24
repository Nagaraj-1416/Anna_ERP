<div>
    <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;font-size: 12px;">
        <thead>
        <tr style="background-color: #2f3d4a;">
            <th style="height: 35px;color: #fff;text-align: left;width: 15%;padding-left: 10px;">EXPENSE#</th>
            <th style="text-align: left;color: #fff;width: 15%;">DATE</th>
            <th style="text-align: left;color: #fff;width: 15%;">TYPE</th>
            <th style="text-align: left;color: #fff;width: 15%;">CUSTOMER</th>
            <th style="text-align: left;color: #fff;width: 15%;">SUPPLIER</th>
            <th style="text-align: right;color: #fff;width: 15%;padding-right: 10px;">AMOUNT</th>
        </tr>
        </thead>
        <tbody>
        @if($expenses)
            @foreach($expenses as $key => $values)
                @if($values->count())
                    <tr>
                        <td style="padding-left: 10px; padding-top: 10px;" colspan="6">
                            <b>{{ $model->find($key)->$get ?? 'None' }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <hr>
                        </td>
                    </tr>
                    @foreach($values as $key => $expense)
                        <tr>
                            <td style="vertical-align: middle;width: 15%;height: 35px;padding-left: 10px;">{{ $expense->expense_no }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $expense->expense_date }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $expense->expense_type }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $expense->customer->display_name ?? 'None' }}</td>
                            <td style="vertical-align: middle;text-align: left;width: 15%;">{{ $expense->supplier->display_name ?? 'None' }}</td>
                            <td style="vertical-align: middle;text-align: right;width: 20%;padding-right: 10px;">{{ number_format($expense->amount) }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        @endif
        <tr>
            <td colspan="5"
                style="text-align: right;border-top: 1px solid #D0D0D0;padding-right: 25px;">
                <strong>Total</strong>
            </td>
            <td style="text-align: right;border-top: 1px solid #D0D0D0;width: 14%;padding-right: 10px;">
                <strong>{{ number_format($total) }}</strong>
            </td>
        </tr>
        </tbody>
    </table>
</div>