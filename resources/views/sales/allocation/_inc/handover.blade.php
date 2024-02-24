@if($handover)
    <div class="card card-body">
        <h3>
            <b>HANDOVER</b> |
            <small class="{{ statusLabelColor($handover->status) }}">
                {{ $handover->status }}
            </small>
            <span class="pull-right">
                @if(count($expenses))
                    {{--#{{ $handover->code }}--}}
                    @if($allocation->status == 'Progress' && $handover->status == 'Pending')
                        {{--@if(!((isCashierLevelStaff() && $handover->is_cashier_approved == 'Yes') || (isStoreLevelStaff() && $handover->is_sk_approved == 'Yes')))--}}
                        @if(isCashierLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff())
                            <a target="_blank" href="{{ route('sales.allocation.handover.approval', [$allocation, $handover])  }}"
                               class="btn btn-info">
                                <i class="fa fa-check"></i> Confirm Handover
                            </a>
                        @endif
                    @endif
                @endif
                @if(isDirectorLevelStaff())
                    @if(!isNextDayAllocationAvailable($allocation))
                        <a target="_blank" href="{{ route('sales.allocation.handover.edit', [$allocation, $handover])  }}"
                           class="btn btn-primary">
                            <i class="fa fa-edit"></i> Update Handover
                        </a>
                    @endif
                @endif
            </span>
        </h3>
        <hr>
        @if(isCashierLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff())
            <div class="row">
                <div class="col-md-3">
                    <h5><b>Collection today's sales</b></h5>
                    <table class="ui celled structured table">
                        <tbody>
                        <tr>
                            <td><b>Cash</b></td>
                            <td class="text-right">{{ number_format($handover->cash_sales, 2) }}</td>
                        </tr>
                        <tr>
                            <td><b>Cheque</b></td>
                            <td class="text-right">{{ number_format($handover->cheque_sales, 2) }}</td>
                        </tr>
                        <tr>
                            <td><b>Deposit</b></td>
                            <td class="text-right">{{ number_format($handover->deposit_sales, 2) }}</td>
                        </tr>
                        <tr>
                            <td><b>Card</b></td>
                            <td class="text-right">{{ number_format($handover->card_sales, 2) }}</td>
                        </tr>
                        <tr>
                            <td><b>Credit</b></td>
                            <td class="text-right">{{ number_format($handover->credit_sales, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="td-bg-info"><b>Total</b></td>
                            <td class="td-bg-success text-right">
                                <b>{{ number_format($handover->sales, 2) }}</b>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                @if($handover->dailySale && $handover->dailySale->sales_location == 'Van')
                    <div class="col-md-3">
                        <h5><b>Collection old sales</b></h5>
                        <table class="ui celled structured table">
                            <tbody>
                            <tr>
                                <td><b>Cash</b></td>
                                <td class="text-right">{{ number_format($handover->old_cash_sales, 2) }}</td>
                            </tr>
                            <tr>
                                <td><b>Cheque</b></td>
                                <td class="text-right">{{ number_format($handover->old_cheque_sales, 2) }}</td>
                            </tr>
                            <tr>
                                <td><b>Deposit</b></td>
                                <td class="text-right">{{ number_format($handover->old_deposit_sales, 2) }}</td>
                            </tr>
                            <tr>
                                <td><b>Card</b></td>
                                <td class="text-right">{{ number_format($handover->old_card_sales, 2) }}</td>
                            </tr>
                            <tr>
                                <td><b>Credit</b></td>
                                <td class="text-right">{{ number_format($handover->old_credit_sales, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="td-bg-info"><b>Total</b></td>
                                <td class="td-bg-success text-right">
                                    <b>{{ number_format($handover->old_sales, 2) }}</b>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
                @if($handover->dailySale && $handover->dailySale->sales_location == 'Van')
                    <div class="col-md-3">
                        <h5><b>Returned chq collection</b></h5>
                        <table class="ui celled structured table">
                            <tbody>
                            <tr>
                                <td><b>Cash</b></td>
                                <td class="text-right">{{ number_format($handover->rc_cash, 2) }}</td>
                            </tr>
                            <tr>
                                <td><b>Cheque</b></td>
                                <td class="text-right">{{ number_format($handover->rc_cheque, 2) }}</td>
                            </tr>
                            <tr>
                                <td><b>Deposit</b></td>
                                <td class="text-right">{{ number_format($handover->rc_deposit, 2) }}</td>
                            </tr>
                            <tr>
                                <td><b>Card</b></td>
                                <td class="text-right">{{ number_format($handover->rc_card, 2) }}</td>
                            </tr>
                            <tr>
                                <td><b>Credit</b></td>
                                <td class="text-right">{{ number_format($handover->rc_credit, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="td-bg-info"><b>Total</b></td>
                                <td class="td-bg-success text-right">
                                    <b>{{ number_format($handover->rc_collection, 2) }}</b>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
                <div class="col-md-3">
                    <h5><b>Total Collection</b></h5>
                    <table class="ui celled structured table">
                        <tbody>
                        <tr>
                            <td><b>Cash</b></td>
                            <td class="text-right">{{ number_format(($handover->cash_sales + $handover->old_cash_sales + $handover->rc_cash), 2) }}</td>
                        </tr>
                        <tr>
                            <td><b>Cheque</b></td>
                            <td class="text-right">{{ number_format(($handover->cheque_sales + $handover->old_cheque_sales + $handover->rc_cheque), 2) }}</td>
                        </tr>
                        <tr>
                            <td><b>Deposit</b></td>
                            <td class="text-right">{{ number_format(($handover->deposit_sales + $handover->old_deposit_sales + $handover->rc_deposit), 2) }}</td>
                        </tr>
                        <tr>
                            <td><b>Card</b></td>
                            <td class="text-right">{{ number_format(($handover->card_sales + $handover->old_card_sales + $handover->rc_card), 2) }}</td>
                        </tr>
                        <tr>
                            <td><b>Credit</b></td>
                            <td class="text-right">{{ number_format(($handover->credit_sales + $handover->old_credit_sales + $handover->rc_credit), 2) }}</td>
                        </tr>
                        <tr>
                            <td class="td-bg-info"><b>Total collection</b></td>
                            <td class="text-right td-bg-success">
                                <b>{{ number_format($handover->total_collect, 2) }}</b>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row m-t-20">
                <div class="col-md-12">
                    <h5><b>Summary</b></h5>
                    <table class="ui celled structured table">
                        <tbody>
                        <tr>
                            <td colspan="6">
                                <b class="text-purple">Total
                                    Collection: </b>{{ number_format($handover->total_collect, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-info text-right"><b>Cash Collection</b></td>
                            <td class="text-danger text-right"><b>Total expenses</b></td>
                            <td class="text-danger text-right"><b>Refunded</b></td>
                            <td class="text-danger text-right"><b>Shortage</b></td>
                            <td class="text-warning text-right"><b>Excess</b></td>
                            <td class="text-green text-right"><b>Balance</b></td>
                        </tr>
                        <tr>
                            <td class="td-bg-info text-right">
                                {{  number_format(($handover->cash_sales + $handover->old_cash_sales + $handover->rc_cash), 2) }}
                            </td>
                            <td class="td-bg-danger text-right">
                                {{  number_format($handover->total_new_expense, 2) }}
                            </td>
                            <td class="td-bg-danger text-right">
                                {{ number_format($refundedAmount, 2) }}
                            </td>
                            <td class="td-bg-danger text-right">
                                {{ number_format($handover->shortage, 2) }}
                            </td>
                            <td class="td-bg-warning text-right">
                                {{ number_format($handover->excess, 2) }}
                            </td>
                            <td class="td-bg-success text-right">
                                {{ number_format((($handover->cash_sales + $handover->old_cash_sales + + $handover->rc_cash + $handover->excess) - ($handover->total_new_expense + $handover->shortage + $refundedAmount)), 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <b class="text-megna text-right">
                                    Cheque Collection: </b> {{ number_format(($handover->cheque_sales + $handover->old_cheque_sales + $handover->rc_cheque), 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <b class="text-megna text-right">
                                    Direct Deposits: </b> {{ number_format($handover->deposit_total, 2) }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- sales expenses -->
            <div class="row">
                <div class="col-md-12 m-t-20">
                    @if($expenses->count())
                        <h5><b>Expenses</b></h5>
                        <table class="ui celled structured table">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Date & time</th>
                                <th>Type</th>
                                <th>Notes</th>
                                <th class="text-right">Old Amount</th>
                                <th class="text-right">Amount</th>
                                <th>Map</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($expenses as $expense)
                                <tr>
                                    <td>
                                        {{ $expense->code ?? 'None' }}
                                    </td>
                                    <td>
                                        {{ $expense->expense_date ?? 'None' }}
                                        {{ $expense->expense_time ? 'at '.$expense->expense_time : '' }}
                                    </td>
                                    <td>
                                        {{ $expense->type->name or 'None' }}
                                        @if($expense->type_id == 2)
                                            <br />
                                            <b>Ltr:</b> {{ $expense->liter }}<br />
                                            <b>ODO Reading: </b>{{ $expense->odometer }}
                                        @endif
                                    </td>
                                    <td>{{ $expense->notes }}</td>
                                    <td class="text-right">{{ number_format($expense->amount, 2) }}</td>
                                    <td class="text-right">{{ number_format($expense->expense->amount ?? 0, 2) }}</td>
                                    <td>
                                        @if($expense->gps_lat && $expense->gps_long)
                                            <a target="_blank" href="{{ route('map.index', [
                                                            'startLat' => $expense->gps_lat,
                                                            'startLng' => $expense->gps_long,
                                                            'startInfo' => json_encode(['heading' => $expense->code, 'code' => $expense->expense_date]),
                                                            ]) }}">View in Map</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="text-right td-bg-info"><b>Total</b></td>
                                <td class="td-bg-success text-right">
                                    <b>{{ number_format($handover->total_expense, 2) }}</b></td>
                                <td class="td-bg-success text-right">
                                    <b>{{ number_format($handover->total_new_expense, 2) }}</b></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- cash breakdown details -->
            <div class="row">
                @if($handover->breakdowns->count())
                    <div class="col-md-6 m-t-20">
                        <h5><b>Cash Breakdowns</b></h5>
                        <table class="ui celled structured table">
                            <thead>
                            <tr>
                                <th>Rupee</th>
                                <th>Count</th>
                                <th class="text-right">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($handover->breakdowns as $breakdown)
                                <tr>
                                    <td>{{ $breakdown->rupee_type }}</td>
                                    <td>{{ $breakdown->count }}</td>
                                    <td class="text-right">{{ number_format(($breakdown->rupee_type * $breakdown->count), 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="td-bg-info text-right"><b>Total</b></td>
                                <td class="td-bg-success text-right">
                                    <b>  {{ number_format(getBreakDownTotal($handover->breakdowns), 2) }}</b>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="row m-t-20">
                @if($cheques->count())
                    <div class="col-md-12">
                        <h5><b>Cheques in Hand</b></h5>
                        <table class="ui celled structured table">
                            <thead>
                            <tr>
                                <th>Invoice no</th>
                                <th>Payment date</th>
                                <th>Cheque type</th>
                                <th>Cheque no</th>
                                <th>Cheque date</th>
                                <th>Written bank</th>
                                <th>Shortage</th>
                                <th class="text-right">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cheques as $cheque)
                                <tr>
                                    <td>
                                        @if($cheque instanceof \App\InvoicePayment)
                                            <a target="_blank"
                                               href="{{ route('sales.invoice.show', [$cheque->invoice]) }}">{{ $cheque->invoice->ref ?? 'None' }}</a>
                                        @else
                                            <a target="_blank"
                                               href="{{ route('sales.invoice.show', [$cheque->chequeable->invoice]) }}">{{ $cheque->chequeable->invoice->ref ?? 'None' }}</a>
                                        @endif
                                    </td>
                                    <td>{{$cheque instanceof \App\InvoicePayment ? $cheque->payment_date : ( $cheque->chequeable->payment_date ?? 'None') }}</td>
                                    <td>{{ $cheque->cheque_type ?? 'None' }}</td>
                                    <td>{{ $cheque->cheque_no }}</td>
                                    <td>{{ $cheque->cheque_date }}</td>
                                    <td>{{ $cheque->bank->name ?? 'None' }}</td>
                                    <td>{{ $cheque->shortage ?? 'None' }}</td>
                                    <td class="text-right">{{ number_format($cheque->amount, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="7" class="text-right td-bg-info"><b>Total</b></td>
                                <td class="td-bg-success text-right">
                                    <b>{{ number_format($cheques->sum('amount'), 2) }}</b></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <hr>
        @endif
        <div class="row">
            <div class="col-md-3">
                <p><b>Prepared by :</b> {{ $handover->preparedBy->name ?? 'System' }}</p>
            </div>
            <div class="col-md-3">
                <p><b>Prepared at :</b> {{ date("F j, Y, g:i a", strtotime($handover->created_at)) }}</p>
            </div>
        </div>
    </div>
@endif