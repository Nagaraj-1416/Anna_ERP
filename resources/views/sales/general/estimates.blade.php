<div class="card">
    <div class="card-body">
        <h3><b>ESTIMATES</b> <span class="pull-right">Total Estimates: {{ count($estimates) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Estimate no</th>
                    <th>Estimate date</th>
                    <th>Expiry date</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                </tr>
                </thead>
                <tbody>
                    @if(count($estimates))
                        @foreach($estimates as $estimateKey => $estimate)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('sales.estimate.show', [$estimate]) }}">{{ $estimate->estimate_no }}</a>
                            </td>
                            <td>{{ $estimate->estimate_date }}</td>
                            <td>{{ $estimate->expiry_date }}</td>
                            <td>
                                <span class="{{ statusLabelColor($estimate->status) }}">{{ $estimate->status }}</span>
                            </td>
                            <td class="text-right">{{ number_format($estimate->total, 2) }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>No Estimates Found...</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>