<!-- Overdue Invoices & Bills -->
<div class="card bg-light-danger border-danger">
    <div class="card-body">
        <h3 class="card-title text-danger">Overdue Invoices & Bills</h3>
        <hr>
        <h5><b>Invoices</b></h5>
        <div class="{{ count(overdueInvoices()) > 5 ? 'scrollable-widget' : '' }}">
            <table class="table custom-table m-t-10">
                <tbody>
                @if(count(overdueInvoices()))
                    @foreach(overdueInvoices() as $invoice)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('sales.invoice.show', [$invoice]) }}">
                                    {{ $invoice->customer->display_name }}
                                </a><br />
                                <small class="text-muted">{{ $invoice->ref }}</small>
                                <small class="text-muted"> | {{ $invoice->due_date }}</small>
                            </td>
                            <td class="text-right text-muted">{{ number_format(invOutstanding($invoice)['balance'], 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="2" class="text-muted"><small>No overdue invoices found...</small></td></tr>
                @endif
                </tbody>
            </table>
        </div>
        <hr>
        <h5><b>Bills</b></h5>
        <div class="{{ count(overdueBills()) > 5 ? 'scrollable-widget' : '' }}">
            <table class="table custom-table m-t-10">
                <tbody>
                @if(count(overdueBills()))
                    @foreach(overdueBills() as $bill)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('purchase.bill.show', [$bill]) }}">
                                    {{ $bill->supplier->display_name }}
                                </a><br />
                                <small class="text-muted">{{ $bill->bill_no }}</small>
                                <small class="text-muted"> | {{ $bill->due_date }}</small>
                            </td>
                            <td class="text-right text-muted">{{ number_format($bill->amount, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="2" class="text-muted"><small>No overdue bills found...</small></td></tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>