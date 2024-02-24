@extends('layouts.master')
@section('title', 'Banking')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Banking') !!}
@endsection
@section('content')
<div class="row m-t-10">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <h3>
                    <b>CASH ACCOUNTS</b>
                </h3>
                <h6 class="card-subtitle">Cash accounts' balance and total cash in hand as at {{ carbon()->now()->format('F j, Y') }}</h6>
                <hr>
                <div class="table-responsive">
                    <table class="table color-bordered-table muted-bordered-table">
                        <thead>
                        <tr>
                            <th>Account name</th>
                            <th style="width: 15%; text-align: right;">Debit</th>
                            <th style="width: 15%; text-align: right;">Credit</th>
                            <th style="width: 15%; text-align: right;">Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($cashAccounts)
                                @foreach($cashAccounts as $keyCash => $cashAccount)
                                    <tr>
                                        <td>
                                            <a target="_blank" href="{{ route('finance.account.show', [$cashAccount]) }}">
                                                {{ $cashAccount->name }}<small><code>{{ ' - '.$cashAccount->code }}</code></small>
                                                <div class="account-details">
                                                    <small class="text-muted">
                                                        {{ $cashAccount->latest_tx_date ? 'Last transaction on '.carbon($cashAccount->latest_tx_date)->format('F j, Y') : 'No transactions for this account' }}
                                                    </small>
                                                    @if($cashAccount->opening_balance)
                                                        <br/>
                                                        <small class="text-muted">
                                                            <cite><b>Opening balance: </b>{{ number_format($cashAccount->opening_balance, 2) }} as at {{ carbon($cashAccount->opening_balance_at)->format('F j, Y') }}</cite>
                                                        </small>
                                                    @endif
                                                    @if($cashAccount->notes)
                                                        <br />
                                                        <small class="text-muted">
                                                            <b>Notes:</b> {{ $cashAccount->notes }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </a>
                                        </td>
                                        <td style="text-align: right;">{{ number_format(accBalance($cashAccount)['debit'], 2) }}</td>
                                        <td style="text-align: right;">{{ number_format(accBalance($cashAccount)['credit'], 2) }}</td>
                                        <td style="text-align: right;">{{ number_format(accBalance($cashAccount)['balance'], 2) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="4">No Cash Accounts Found...</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3>
                    <b>BANK ACCOUNTS</b>
                </h3>
                <h6 class="card-subtitle">Bank accounts' balance and total cash in bank as at {{ carbon()->now()->format('F j, Y') }}</h6>
                <hr>
                <div class="clearfix">
                    <div class="pull-left"></div>
                    <div class="pull-right">

                    </div>
                </div>
                <div class="table-responsive m-t-10">
                    <table class="table color-bordered-table muted-bordered-table">
                        <thead>
                        <tr>
                            <th>Account name</th>
                            <th style="width: 15%; text-align: right;">Debit</th>
                            <th style="width: 15%; text-align: right;">Credit</th>
                            <th style="width: 15%; text-align: right;">Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($bankAccounts)
                            @foreach($bankAccounts as $keyBank => $bankAccount)
                                <tr>
                                    <td>
                                        <a target="_blank" href="{{ route('finance.account.show', [$bankAccount]) }}">
                                            {{ $bankAccount->name }}<small><code>{{ ' - '.$bankAccount->code }}</code></small>
                                            <div class="account-details">
                                                <small class="text-muted">
                                                    {{ $bankAccount->latest_tx_date ? 'Last transaction on '.carbon($bankAccount->latest_tx_date)->format('F j, Y') : 'No transactions for this account' }}
                                                </small>
                                                @if($bankAccount->opening_balance)
                                                    <br/>
                                                    <small class="text-muted">
                                                        <cite><b>Opening balance: </b>{{ number_format($bankAccount->opening_balance, 2) }} as at {{ carbon($bankAccount->opening_balance_at)->format('F j, Y') }}</cite>
                                                    </small>
                                                @endif
                                                @if($bankAccount->notes)
                                                    <br />
                                                    <small class="text-muted">
                                                        <b>Notes:</b> {{ $bankAccount->notes }}
                                                    </small>
                                                @endif
                                            </div>
                                        </a>
                                    </td>
                                    <td style="text-align: right;">{{ number_format(accBalance($bankAccount)['debit'], 2) }}</td>
                                    <td style="text-align: right;">{{ number_format(accBalance($bankAccount)['credit'], 2) }}</td>
                                    <td style="text-align: right;">{{ number_format(accBalance($bankAccount)['balance'], 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="4">No Bank Accounts Found...</td></tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card border-blue">
            <div class="card-body">
                <h3 class="text-blue">
                    <b>CHEQUES IN HAND</b>
                    <span class="pull-right">
                        <a target="_blank" href="{{ route('finance.cheques.hand.create') }}" class="btn waves-effect waves-light btn-success btn-sm">Register Cheque</a>
                        <a target="_blank" href="{{ route('finance.cheques.hand.by.registered.date') }}" class="btn waves-effect waves-light btn-primary btn-sm">By Registered Date</a>
                        <a target="_blank" href="{{ route('finance.cheques.hand.index') }}" class="btn waves-effect waves-light btn-info btn-sm">By Written Date</a>
                    </span>
                </h3>
                <h6 class="card-subtitle text-blue">Cheques status counts as at {{ carbon()->now()->format('F j, Y') }}</h6>
                <hr>
                <div class="row text-center">
                    <div class="col-lg-3 col-md-3">
                        <h1 class="m-b-0 font-light">{{ count($totalCIHs) }}</h1>Total</div>
                    <div class="col-lg-3 col-md-3">
                        <h1 class="m-b-0 font-light">{{ count($notRealisedCIHs) }}</h1>Not Realised</div>
                    <div class="col-lg-3 col-md-3">
                        <h1 class="m-b-0 font-light">{{ count($depositedCIHs) }}</h1>Deposited</div>
                    <div class="col-lg-3 col-md-3">
                        <h1 class="m-b-0 font-light">{{ count($realisedCIHs) }}</h1>Realised</div>
                </div>
                <hr>
                <h6 class="card-subtitle text-blue">Today's dated cheques</h6>
                <div class="table-responsive m-t-10">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Registered date</th>
                                <th>Type</th>
                                <th>Written bank</th>
                                <th>Dated on</th>
                                <th>Status</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($todayCIHs)
                                @foreach($todayCIHs as $chequeNo => $todayCIH)
                                    <tr style="background-color: #e0e7eb;">
                                        <td colspan="6"><b>Cheque no:</b> {{ $chequeNo }}</td>
                                    </tr>
                                    @foreach($todayCIH as $keyCIH => $cheque)
                                        <tr>
                                            <td>
                                                {{ $cheque->registered_date }}<br />
                                                @if($cheque->type == 'Auto')
                                                    @if($cheque->chequeable instanceof \App\InvoicePayment)
                                                        <a target="_blank" href="{{ route('sales.invoice.show', ['invoice' => $cheque->chequeable->invoice]) }}">View Invoice</a>
                                                    @endif
                                                @else
                                                    <a target="_blank" href="{{ route('finance.trans.show', ['trans' => $cheque->transaction->id]) }}">View Journal</a>
                                                @endif
                                            </td>
                                            <td>{{ $cheque->cheque_type }}</td>
                                            <td>{{ $cheque->bank->name }}</td>
                                            <td>{{ $cheque->cheque_date }}</td>
                                            <td>
                                                <span class="{{ statusLabelColor($cheque->status) }}">{{ $cheque->status }}</span>
                                            </td>
                                            <td class="text-right">{{ number_format($cheque->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @else
                                <tr><td colspan="6">No cheques found...</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-warning">
            <div class="card-body">
                <h3 class="text-warning">
                    <b>DEPOSITED CHEQUES</b>
                    <span class="pull-right">
                        <a href="{{ route('finance.cheques.deposited') }}" class="btn waves-effect waves-light btn-info btn-sm">View More</a>
                    </span>
                </h3>
                <h6 class="card-subtitle text-warning">Today's deposited cheques</h6>
                <hr>
                <div class="clearfix">
                    <div class="pull-left"></div>
                    <div class="pull-right">

                    </div>
                </div>
                <div class="table-responsive m-t-10">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Registered date</th>
                            <th>Written bank</th>
                            <th>Dated on</th>
                            <th>Status</th>
                            <th class="text-right">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr><td colspan="6">No cheques found...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-purple">
            <div class="card-body">
                <h3 class="text-purple">
                    <b>ISSUED CHEQUES</b>
                    <span class="pull-right">
                        <a href="{{ route('finance.issued.cheque.index') }}" class="btn waves-effect waves-light btn-info btn-sm">View More</a>
                    </span>
                </h3>
                <h6 class="card-subtitle text-purple">Today's issued cheques</h6>
                <hr>
                <div class="clearfix">
                    <div class="pull-left"></div>
                    <div class="pull-right">

                    </div>
                </div>
                <div class="table-responsive m-t-10">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Registered date</th>
                            <th>Written bank</th>
                            <th>Dated on</th>
                            <th>Status</th>
                            <th class="text-right">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr><td colspan="6">No cheques found...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-danger">
            <div class="card-body">
                <h3 class="text-danger">
                    <b>BOUNCED CHEQUES</b>
                    <span class="pull-right">
                        <a href="" class="btn waves-effect waves-light btn-info btn-sm">View More</a>
                    </span>
                </h3>
                <h6 class="card-subtitle text-danger">Today's bounced cheques</h6>
                <hr>
                <div class="clearfix">
                    <div class="pull-left"></div>
                    <div class="pull-right">

                    </div>
                </div>
                <div class="table-responsive m-t-10">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Registered date</th>
                            <th>Written bank</th>
                            <th>Dated on</th>
                            <th>Status</th>
                            <th class="text-right">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr><td colspan="6">No cheques found...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')

@endsection

@section('script')
    <script>

    </script>
@endsection