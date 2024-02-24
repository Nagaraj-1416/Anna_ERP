<div class="card">
    <div class="card-body">
        <h3><b>CREDITS</b> <span class="pull-right">Total Credits: {{ count($credits) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Credit no</th>
                    <th>Credit date</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">Refunded</th>
                    <th class="text-right">Credited</th>
                    <th class="text-right">Remaining</th>
                </tr>
                </thead>
                <tbody>
                    @if(count($credits))
                        @foreach($credits as $creditKey => $credit)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('purchase.credit.show', [$credit]) }}">{{ $credit->code }}</a>
                            </td>
                            <td>{{ $credit->date }}</td>
                            <td>
                                <span class="{{ statusLabelColor($credit->status) }}">{{ $credit->status }}</span>
                            </td>
                            <td class="text-right text-success">{{ number_format($credit->amount, 2) }}</td>
                            <td class="text-right text-green">{{ number_format($credit->refunds->sum('amount'), 2) }}</td>
                            <td class="text-right text-green">{{ number_format($credit->payments->sum('payment'), 2) }}</td>
                            <td class="text-right text-warning">{{ number_format(getSupplierCreditLimit($credit), 2) }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>No Credits Found...</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>