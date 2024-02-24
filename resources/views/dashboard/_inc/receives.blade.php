<div class="card border-default">
    <div class="card-body">
        <h3 class="card-title text-megna">Cash & Cheque Received</h3>
        <table class="table custom-table m-t-10">
            <tbody>
            @if(count(pendingTransfers()))
                @foreach(pendingTransfers() as $pendingTransfer)
                    <tr>
                        <td>
                            <h6>
                                <a target="_blank" href="{{ route('finance.transfer.show', $pendingTransfer) }}">
                                    Cash | {{ number_format($pendingTransfer->amount, 2) }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                <b>Transferred on:</b> {{ carbon($pendingTransfer->date)->format('F j, Y') }}
                                <br />
                                <b>Sender:</b> {{ $pendingTransfer->receiverCompany->name }}
                            </small>

                        </td>
                        <td class="text-right">
                            <span class="{{ statusLabelColor($pendingTransfer->status) }}">
                                {{ $pendingTransfer->status }}
                            </span><br />
                            <a target="_blank" href="{{ route('finance.transfer.show', $pendingTransfer) }}" class="btn btn-info btn-sm pull-right">
                                <i class="ti-check"></i> Approve
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2" class="text-muted">
                        <span>No transfers received...</span>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>