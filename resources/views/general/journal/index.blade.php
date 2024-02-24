<div class="card">
    <div class="card-body">
        <h3><b>JOURNALS</b> <span class="pull-right">Total Journals: {{ count($journals) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Journal no</th>
                    <th>Date</th>
                    <th>Auto/Manual</th>
                    <th>Type</th>
                    <th class="text-right">Amount</th>
                </tr>
                </thead>
                <tbody>
                @if(count($journals))
                    @foreach($journals as $journalKey => $journal)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('finance.trans.show', [$journal]) }}">{{ $journal->code }}</a>
                            </td>
                            <td>{{ $journal->date }}</td>
                            <td>{{ $journal->category }}</td>
                            <td>{{ $journal->type }}</td>
                            <td class="text-right">{{ number_format($journal->amount, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>No Journals Found...</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>