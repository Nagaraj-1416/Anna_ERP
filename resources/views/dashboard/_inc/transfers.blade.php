<div class="card border-danger">
    <div class="card-body">
        <h3 class="card-title text-danger">Cash & Cheque Transfers</h3>
        <hr>
        <div class="clearfix">
            <div class="pull-left">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Transfer
                    </button>
                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                        @if(isDirectorLevelStaff() || isAccountLevelStaff() || isCashierLevelStaff() || isStoreLevelStaff())
                            <a class="dropdown-item" href="{{ route('finance.transfer.create') }}?type=Cash">Cash</a>
                            <a class="dropdown-item" href="{{ route('finance.transfer.create') }}?type=Cheque">Cheque</a>
                        @endif
                        @if(isShopLevelStaff() || isShopManagerLevelStaff())
                            <a class="dropdown-item" href="{{ route('finance.transfer.shop.create') }}?type=Cash">Shop Cash</a>
                            <a class="dropdown-item" href="{{ route('finance.transfer.shop.create') }}?type=Cheque">Shop Cheque</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <a target="_blank" href="{{ route('finance.transfer.index') }}" class="btn btn-sm btn-info">View All</a>
            </div>
        </div>

        <hr>
        <div class="clearfix">
            <div class="pull-left">
                <span><b>Pending Transfers</b></span>
            </div>
            <div class="pull-right"></div>
        </div>

        <table class="table custom-table m-t-10">
            <tbody>
                @if(count(recentTransfers()))
                    @foreach(recentTransfers() as $transfer)
                    <tr>
                        <td>
                            <h6>
                                <a target="_blank" href="{{ route('finance.transfer.show', $transfer) }}">
                                    {{ $transfer->type }} | {{ number_format($transfer->amount, 2) }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                <b>Transferred on:</b> {{ carbon($transfer->date)->format('F j, Y') }}
                                <br />
                                <b>Receiver:</b> {{ $transfer->receiverCompany->name }}
                            </small>
                        </td>
                        <td>
                            <span class="{{ statusLabelColor($transfer->status) }}">
                                {{ $transfer->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="text-muted">
                            <span>No transfers found...</span>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <hr>
        <div class="clearfix">
            <div class="pull-left">
                <span><b>Drafted Transfers</b></span>
            </div>
            <div class="pull-right"></div>
        </div>

        <table class="table custom-table m-t-10">
            <tbody>
            @if(count(draftedTransfers()))
                @foreach(draftedTransfers() as $draftTransfer)
                    <tr>
                        <td>
                            <h6>
                                <a target="_blank" href="{{ route('finance.transfer.show', $draftTransfer) }}">
                                    {{ $draftTransfer->type }} | {{ number_format($draftTransfer->amount, 2) }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                <b>Transferred on:</b> {{ carbon($draftTransfer->date)->format('F j, Y') }}
                                <br />
                                <b>Receiver:</b> {{ $draftTransfer->receiverCompany->name }}
                            </small>
                            <br />
                            <code>
                                Please upload the deposited slip to send this tranfer for approval
                            </code>
                        </td>
                        <td>
                            <span class="{{ statusLabelColor($draftTransfer->status) }}">
                                {{ $draftTransfer->status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2" class="text-muted">
                        <span>No transfers found...</span>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>