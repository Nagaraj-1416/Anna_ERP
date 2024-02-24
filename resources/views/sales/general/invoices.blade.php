<div class="card">
    <div class="card-body">
        <h3><b>INVOICES</b> <span class="pull-right">Total Invoices: {{ count($invoices) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                    <tr>
                        <th>Invoice no</th>
                        <th>Invoice date</th>
                        <th>Due date</th>
                        <th>Status</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">Paid</th>
                        <th class="text-right">Balance</th>
                    </tr>
                </thead>
                <tbody>
                @if(count($invoices))
                    @foreach($invoices as $invKey => $invoice)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('sales.invoice.show', [$invoice]) }}">{{ $invoice->ref }}</a>
                            </td>
                            <td>{{ $invoice->invoice_date }}</td>
                            <td>{{ $invoice->due_date }}</td>
                            <td>
                                <span class="{{ statusLabelColor($invoice->status) }}">{{ $invoice->status }}</span>
                            </td>
                            <td class="text-right">{{ number_format($invoice->amount, 2) }}</td>
                            <td class="text-right text-green">{{ number_format(invOutstanding($invoice)['paid'], 2) }}</td>
                            <td class="text-right text-warning">{{ number_format(invOutstanding($invoice)['balance'], 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>No Invoices Found...</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>