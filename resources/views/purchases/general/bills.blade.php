<div class="card">
    <div class="card-body">
        <h3><b>BILLS</b> <span class="pull-right">Total Bills: {{ count($bills) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                    <tr>
                        <th>Bill no</th>
                        <th>Bill date</th>
                        <th>Status</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">Paid</th>
                        <th class="text-right">Balance</th>
                    </tr>
                </thead>
                <tbody>
                @if(count($bills))
                    @foreach($bills as $billKey => $bill)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('purchase.bill.show', [$bill]) }}">{{ $bill->bill_no }}</a>
                            </td>
                            <td>{{ $bill->bill_date }}</td>
                            <td>
                                <span class="{{ statusLabelColor($bill->status) }}">{{ $bill->status }}</span>
                            </td>
                            <td class="text-right">{{ number_format($bill->amount, 2) }}</td>
                            <td class="text-right text-green">{{ number_format(billOutstanding($bill)['paid'], 2) }}</td>
                            <td class="text-right text-warning">{{ number_format(billOutstanding($bill)['balance'], 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>No Bills Found...</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>