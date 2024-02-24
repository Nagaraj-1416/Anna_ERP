<div class="card">
    <div class="card-body">
        <h3><b>GRNS</b> <span class="pull-right">Total GRNs: {{ count($grns) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                    <tr>
                        <th>GRN Code</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-center">No of Items</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                @if(count($grns))
                    @foreach($grns as $grnKey => $grn)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('purchase.grn.show', [$grn]) }}">{{ $grn->code }}</a>
                            </td>
                            <td>{{ $grn->date }}</td>
                            <td>
                                <span class="{{ statusLabelColor($grn->status) }}">{{ $grn->status }}</span>
                            </td>
                            <td class="text-center">{{ $grn->items()->count() }}</td>
                            <td class="text-right text-warning">{{ number_format($grn->items()->sum('amount'), 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>No GRNs Found...</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>