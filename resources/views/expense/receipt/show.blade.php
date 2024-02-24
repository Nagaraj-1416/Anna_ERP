@extends('layouts.master')
@section('title', 'Payment Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Expense') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Payment Details</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @can('edit', $expense)
                                    <a href="{{ route('expense.receipt.edit', [$expense->id]) }}"
                                       class="btn waves-effect waves-light btn-primary btn-sm"
                                       target="_blank">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                @endcan
                                @can('edit', $expense)
                                    <button class="btn btn-danger btn-sm exp-delete">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                @endcan
                                @can('edit', $expense)
                                    <a href="{{ route('expense.receipt.add.item', [$expense->id]) }}"
                                       class="btn waves-effect waves-light btn-info btn-sm"
                                       target="_blank">
                                        <i class="fa fa-plus"></i> Add Expense Items
                                    </a>
                                @endcan
                                {{--<a href="{{ route('expense.receipt.add.item', [$expense->id]) }}"
                                   class="btn waves-effect waves-light btn-info btn-sm"
                                   target="_blank">
                                    <i class="fa fa-plus"></i> Add Payments
                                </a>--}}
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-plus"></i> Add Payments
                                    </button>
                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <a target="_blank" class="dropdown-item" href="{{ route('expense.receipt.add.payment', [$expense->id, 'Cash']) }}">Cash</a>
                                        <a target="_blank" class="dropdown-item" href="{{ route('expense.receipt.add.payment', [$expense->id, 'Bank']) }}">Bank</a>
                                        <a target="_blank" class="dropdown-item" href="{{ route('expense.receipt.add.payment', [$expense->id, 'OwnCheque']) }}">Own Cheque</a>
                                        <a target="_blank" class="dropdown-item" href="{{ route('expense.receipt.add.payment', [$expense->id, 'ThirdPartyCheque']) }}">Third Party Cheques</a>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('expense.receipt.export', [$expense]) }}"
                                   class="btn waves-effect waves-light btn-inverse btn-sm">
                                    <i class="fa fa-file-pdf-o"></i> Export to PDF
                                </a>
                                <a href="{{ route('expense.receipt.print', [$expense]) }}"
                                   class="btn waves-effect waves-light btn-inverse btn-sm">
                                    <i class="fa fa-print"></i> Print
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- estimate summary and history -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body">
                                <h3>
                                    <b>{{ number_format($expense->amount, 2) }}</b> |
                                    <span>
                                        {{ $expense->expense_date }}
                                    </span>
                                    <span class="pull-right">#{{ $expense->expense_no }}</span></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Expense mode :</b> {{ $expense->expense_mode }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Expense category :</b> {{ $expense->expense_category }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Paid By :</b> {{ $expense->company->name }}</p>
                                    </div>
                                    @if($expense->expense_mode == 'ForOthers' && $expense->expense_category == 'Office')
                                    <div class="col-md-3">
                                        <p><b>Paid to (Branch) :</b> {{ $expense->branch->name or 'None' }}</p>
                                    </div>
                                    @endif
                                    @if($expense->expense_mode == 'ForOthers' && $expense->expense_category == 'Shop')
                                    <div class="col-md-3">
                                        <p><b>Paid to (Shop) :</b> {{ $expense->shop->name or 'None'  }}</p>
                                    </div>
                                    @endif
                                    <div class="col-md-3">
                                        <p><b>Approval required? :</b> {{ $expense->approval_required or 'None'  }}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Expense type :</b> {{ $expense->type->name or 'None' }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    @if(in_array($expense->type_id, ['12', '13', '11', '15', '20', '21', '22', '23', '33']))
                                    <div class="col-md-3"><p><b>Payment month :</b> {{ $expense->month or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['12', '13', '11', '14', '15', '3', '9', '8']))
                                    <div class="col-md-3"><p><b>Staff :</b> {{ $expense->staff->short_name or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['14']))
                                    <div class="col-md-3"><p><b>Installment period :</b> {{ $expense->installment_period or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['3']))
                                    <div class="col-md-3"><p><b>No of days :</b> {{ $expense->no_of_days or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['6', '2', '5', '17', '18', '9', '8', '16']))
                                    <div class="col-md-3"><p><b>Vehicle :</b> {{ $expense->vehicle->vehicle_no or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['2']))
                                    <div class="col-md-3"><p><b>Liters :</b> {{ $expense->liter or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['2']))
                                    <div class="col-md-3"><p><b>ODO at fuel :</b> {{ $expense->odometer or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['6']))
                                    <div class="col-md-3"><p><b>What was repaired? :</b> {{ $expense->what_was_repaired or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['6', '16', '29']))
                                    <div class="col-md-3"><p><b>Changed item :</b> {{ $expense->changed_item or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['6', '29']))
                                    <div class="col-md-3"><p><b>Supplier :</b> {{ $expense->supplier->display_name or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['6', '29']))
                                    <div class="col-md-3"><p><b>Expiry date :</b> {{ $expense->repair_expiry_date or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['6', '29']))
                                    <div class="col-md-3"><p><b>Repairing shop :</b> {{ $expense->repairing_shop or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['6', '16', '29']))
                                    <div class="col-md-3"><p><b>Labour charge :</b> {{ $expense->labour_charge or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['6', '16']))
                                    <div class="col-md-3"><p><b>Driver :</b> {{ $expense->driver->short_name or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['6']))
                                    <div class="col-md-3"><p><b>ODO at repair :</b> {{ $expense->odo_at_repair or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['16']))
                                    <div class="col-md-3"><p><b>Service station :</b> {{ $expense->service_station or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['16']))
                                    <div class="col-md-3"><p><b>ODO at service :</b> {{ $expense->odo_at_service or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['5']))
                                    <div class="col-md-3"><p><b>Parking name :</b> {{ $expense->parking_name or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['17']))
                                    <div class="col-md-3"><p><b>Vehicle maintenance type :</b> {{ $expense->vehicle_maintenance_type or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['17', '24', '25', '27', '7', '26']))
                                    <div class="col-md-3"><p><b>From date :</b> {{ $expense->from_date or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['17', '24', '25', '27', '7', '26']))
                                    <div class="col-md-3"><p><b>To date :</b> {{ $expense->to_date or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['18']))
                                    <div class="col-md-3"><p><b>No of months :</b> {{ $expense->no_of_months or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['9']))
                                    <div class="col-md-3"><p><b>Fine reason :</b> {{ $expense->fine_reason or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['8']))
                                    <div class="col-md-3"><p><b>From destination :</b> {{ $expense->from_destination or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['8']))
                                    <div class="col-md-3"><p><b>To destination :</b> {{ $expense->to_destination or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['8']))
                                    <div class="col-md-3"><p><b>No of bags :</b> {{ $expense->no_of_bags or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['25', '27', '26']))
                                    <div class="col-md-3"><p><b>Account number :</b> {{ $expense->account_number or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['25', '26']))
                                    <div class="col-md-3"><p><b>Units reading :</b> {{ $expense->units_reading or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['29']))
                                    <div class="col-md-3"><p><b>Machine :</b> {{ $expense->machine or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['31']))
                                    <div class="col-md-3"><p><b>Festival name :</b> {{ $expense->festival_name or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['32']))
                                    <div class="col-md-3"><p><b>Donated to :</b> {{ $expense->donated_to or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['32']))
                                    <div class="col-md-3"><p><b>Donated reason :</b> {{ $expense->donated_reason or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['7']))
                                    <div class="col-md-3"><p><b>Hotel :</b> {{ $expense->hotel_name or 'None' }}</p></div>
                                    @endif

                                    @if(in_array($expense->type_id, ['34', '35']))
                                    <div class="col-md-3"><p><b>Bank number :</b> {{ $expense->bank_number or 'None' }}</p></div>
                                    @endif
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><b>Expense account :</b> {{ $expense->expenseAccount->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><b>Prepared by :</b> {{ $expense->preparedBy->name or 'None' }}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    {{--<div class="col-md-3">
                                        <p><b>Payment mode :</b> {{ $expense->payment_mode or 'None' }}</p>
                                    </div>--}}
                                    @if($expense->payment_mode == 'Cheque')
                                    <div class="col-md-3">
                                        <p><b>Cheque type :</b> {{ $expense->cheque_type or 'None' }}</p>
                                    </div>
                                    @endif
                                </div>
                                @if($expense->payment_mode == 'Cheque' && $expense->cheque_type == 'Own')
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><b>Cheque no :</b> {{ $expense->cheque_no or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><b>Cheque date :</b> {{ $expense->cheque_date or 'None' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><b>Bank :</b> {{ $expense->bank->name or 'None' }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($expense->payment_mode == 'Direct Deposit')
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><b>Account no :</b> {{ $expense->account_no or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><b>Deposited date :</b> {{ $expense->deposited_date or 'None' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><b>Bank :</b> {{ $expense->bank->name or 'None' }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($expense->payment_mode == 'Credit Card')
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><b>Card holder name :</b> {{ $expense->card_holder_name or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><b>Card no :</b> {{ $expense->card_no or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><b>Expiry date :</b> {{ $expense->expiry_date or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><b>Bank :</b> {{ $expense->bank->name or 'None' }}</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="card">
                                    <div class="card-body">
                                        <h6><b>CHANGED ITEMS</b></h6>
                                        <hr>
                                        <div class="table-responsive">
                                            <table class="table color-table inverse-table">
                                                <thead>
                                                <tr>
                                                    <th width="20%">Items</th>
                                                    <th width="15%">Expiry Date</th>
                                                    <th>Remarks</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($expense->items)
                                                    @foreach($expense->items as $expItem)
                                                    <tr>
                                                        <td>{{ $expItem->item }}</td>
                                                        <td>{{ $expItem->expiry_date }}</td>
                                                        <td>{{ $expItem->notes }}</td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{--<div class="card">
                                    <div class="card-body">
                                        <h6><b>TRANSACTION RECORDS</b></h6>
                                        <hr>
                                        <div class="table-responsive">
                                            <table class="table color-table inverse-table">
                                                <thead>
                                                    <tr>
                                                        <th width="70%">Account</th>
                                                        <th width="15%" class="text-right">Debit</th>
                                                        <th width="15%" class="text-right">Credit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>--}}
                                                    {{--@if($expense->transaction)
                                                        @foreach($expense->transaction->records as $record)
                                                            <tr>
                                                                <td>{{ $record->account->name or 'None' }}</td>

                                                                <td class="text-right">{{ number_format(($record->type == 'Debit') ? $record->amount : 0.00 , 2)}}</td>
                                                                <td class="text-right">{{ number_format(($record->type == 'Credit') ? $record->amount : 0.00, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif--}}
{{--                                                @else--}}
                                                    {{--<tr>
                                                        <td colspan="3" class="text-center">
                                                            {!! form()->model($expense, ['url' => route('expense.receipt.approve', $expense), 'method' => 'PATCH']) !!}
                                                            Approval is required to generate the transaction
                                                            <br />
                                                            <br />
                                                            <button type="submit" class="btn btn-success">Approve this payment</button>
                                                            {{ form()->close() }}
                                                        </td>
                                                    </tr>--}}
{{--                                                @endif--}}
                                                {{--</tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>--}}

                                <div class="card">
                                    <div class="card-body">
                                        <h3><b>TRANSACTIONS</b> <span class="pull-right"></span></h3>
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

                                <div class="card">
                                    <div class="card-body">
                                        <h6><b>MADE PAYMENTS</b></h6>
                                        <hr>
                                        <div class="table-responsive">
                                            <table class="table color-table inverse-table">
                                                <thead>
                                                    <tr>
                                                        <th width="20%">Mode</th>
                                                        <th>Payment Details</th>
                                                        <th width="15%" style="text-align: right;">Payment</th>
                                                        <th width="10%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($expense->payments)
                                                        @foreach($expense->payments as $expPayment)
                                                            <tr>
                                                                <td>{{ $expPayment->payment_mode }}</td>
                                                                <td>
                                                                    @if($expPayment->payment_mode == "Own Cheque")
                                                                        <span><b>Cheque no: </b>{{ $expPayment->cheque_no or 'None' }}</span><br/>
                                                                        <span><b>Cheque date: </b>{{ $expPayment->cheque_date or 'None' }}</span><br/>
                                                                        <span><b>Written bank: </b>{{ $expPayment->bank->name or 'None' }}</span>
                                                                    @else
                                                                        <span>None</span>
                                                                    @endif
                                                                </td>
                                                                <td style="text-align: right;">{{ number_format($expPayment->payment, 2) }}</td>
                                                                <td>
                                                                    <a href="" class="btn btn-danger btn-sm delete-payment-btn"
                                                                       data-id="{{ $expPayment->id }}">
                                                                        <i class="fa fa-remove"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    <tr>
                                                        <td colspan="2" style="text-align: right;"><b>TOTAL</b></td>
                                                        <td style="text-align: right;"><b>{{ number_format($totalPayments, 2) }}</b></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h6><b>THIRD PARTY CHEQUES</b></h6>
                                        <hr>
                                        <div class="table-responsive">
                                            <table class="table color-table inverse-table">
                                                <thead>
                                                <tr>
                                                    <th width="80%">Cheque Details</th>
                                                    <th width="20%" class="text-right">Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($cheques)
                                                    @foreach($cheques as $chequeKey => $chequeCollect)
                                                        @php
                                                        ['cheque_no' => $chequeNo] = chequeKeyToArray($chequeKey);
                                                        $chequeData = getChequeDataByNo($cheques->first());
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <b>Cheque#</b> <code><b>{{ $chequeNo }}</b></code><br />
                                                                <span class="text-warning">{{ $chequeData['formattedDate'] }}</span>,
                                                                <span class="text-info">{{ $chequeData['bank'] }}</span> <br />
                                                                <span class="text-info"><b>Customer:</b>
                                                                <a href="{{ route('sales.customer.show', $chequeData['customerId']) }}" target="_blank">
                                                                    {{ $chequeData['customer'] }}
                                                                </a>
                                                            </span>
                                                            </td>
                                                            <td class="text-right">
                                                                {{ number_format($chequeData['eachTotal'], 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                <tr>
                                                    <td class="text-right"><b>TOTAL</b></td>
                                                    <td class="text-right">{{ number_format($chequesAmount, 2) }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                @if($expense->notes)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="order-notes">
                                                <h5>Notes</h5>
                                                <small class="text-muted">{{ $expense->notes or 'None' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span
                                                class="pull-right">Total: {{ count($expense->documents) }}</span></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $expense])
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">

                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $expense])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $expense, 'modelName' => 'Expense'])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('general.comment.script', ['modelId' => $expense->id])
    @include('_inc.document.script', ['model' => $expense])
    <script>
        $(document).ready(function () {
            var $delBtn = $('.exp-delete');
            var $delExpPayBtn = $('.delete-payment-btn');
            var deleteRoute = '{{ route('expense.receipt.delete', $expense) }}';
            var indexRoute = '{{ route('expense.receipt.index') }}';
            var expShowRoute = '{{ route('expense.receipt.show', $expense) }}';

            $delBtn.click(function (e) {
                e.preventDefault();
                Swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this transaction!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor : '#fc4b6c',
                    confirmButtonText: 'Yes, Delete it!',
                    cancelButtonText: 'No, Keep it'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: deleteRoute,
                            type: 'DELETE',
                            data: {_token : '{{ csrf_token() }}'},
                            success: function(response) {
                                if (response.success){
                                    Swal(
                                        'Deleted!',
                                        'Your expense payment has been deleted.',
                                        'success'
                                    );
                                    setTimeout(function () {
                                        window.location.href = indexRoute;
                                    }, 2000);
                                }else{
                                    Swal(
                                        'Failed!',
                                        'Your expense payment deleted failed.',
                                        'error'
                                    )
                                }
                            }
                        });
                    }
                })
            });

            $delExpPayBtn.click(function (e) {

                var deleteExpPayRoute = '{{ route('expense.receipt.expense.payment.delete', [$expense, 'expPayment'=>'ID']) }}';
                var expPayId = $(this).data('id');
                deleteExpPayRoute = deleteExpPayRoute.replace('ID', expPayId);

                e.preventDefault();
                Swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this transaction!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor : '#fc4b6c',
                    confirmButtonText: 'Yes, Delete it!',
                    cancelButtonText: 'No, Keep it'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: deleteExpPayRoute,
                            type: 'DELETE',
                            data: {_token : '{{ csrf_token() }}'},
                            success: function(response) {
                                if (response.success){
                                    Swal(
                                        'Deleted!',
                                        'Your expense payment has been deleted.',
                                        'success'
                                    );
                                    setTimeout(function () {
                                        window.location.href = expShowRoute;
                                    }, 2000);
                                }else{
                                    Swal(
                                        'Failed!',
                                        'Your expense payment deleted failed.',
                                        'error'
                                    )
                                }
                            }
                        });
                    }
                })
            });

        });
    </script>
@endsection