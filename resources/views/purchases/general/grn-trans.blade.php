<div class="card">
    <div class="card-body">
        <h3><b>TRANSACTIONS</b> <span class="pull-right">Total Transactions: {{ count($trans) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Narration</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                @if(count($trans))
                    @foreach($trans as $tranKey => $tran)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('finance.trans.show', [$tran]) }}">
                                    {{ $tran->code }}
                                </a>
                            </td>
                            <td>{{ $tran->date }}</td>
                            <td>
                                <a target="_blank" href="{{ route('finance.trans.show', [$tran]) }}">
                                    {{ $tran->auto_narration }}
                                </a>
                            </td>
                            <td class="text-right">{{ number_format($tran->amount, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>No Transactions Found...</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>