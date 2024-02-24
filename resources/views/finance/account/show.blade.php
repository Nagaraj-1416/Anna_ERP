@extends('layouts.master')
@section('title', 'Account Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Account Details') !!}
@endsection
@section('content')
    <div class="row" ng-controller="AccountController">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $account->code }}</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left"></div>
                            <div class="pull-right"></div>
                        </div>
                    </div>

                    <!-- allocation related details -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>ACCOUNT</b> |
                                    <small>{{ $account->name }}</small>
                                    <span class="pull-right">#{{ $account->code }}</span></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>First TX on :</b> {{ $account->first_tx_date or 'None' }} </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p><b>Latest TX on :</b> {{ $account->latest_tx_date or 'None' }} </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Account type :</b> {{ $account->type->name or 'None' }} </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p><b>Account category :</b> {{ $account->category->name or 'None' }} </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p><b>Closing balance carry forward? :</b> {{ $account->closing_bl_carried or 'None' }} </p>
                                    </div>
                                </div>
                                @if($account->notes)
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="order-notes">
                                            <h5>Notes</h5>
                                            <small class="text-muted">{{ $account->notes or 'None' }}</small>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="clearfix">
                                        <div class="pull-left">
                                            <h3><b>CASH FLOW SUMMARY</b></h3>
                                        </div>
                                        <div class="pull-right">
                                            <div class="btn-group">
                                                <a href="{{ $button['preview']['url'] }}" class="btn btn-success">
                                                    <span class="mdi mdi-chevron-left"></span>
                                                </a>
                                                <a href="" class="btn btn-info"> {{ $button['current']['label'] }}</a>
                                                <a  href="{{ $button['next']['url'] }}"  class="btn btn-success">
                                                    <span class="mdi mdi-chevron-right"></span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="pull-right">
                                            <div class="m-r-5">
                                                <form method="get" id="fiter-form">
                                                    <div class="input-group input-daterange">
                                                        <input id="startDate1" value="{{ $from->toDateString() }}" name="from" placeholder="choose from date" type="text" class="form-control">
                                                        <input id="endDate1"  value="{{ $to->toDateString() }}"  name="to" placeholder="choose to date" type="text" class="form-control">
                                                        <button class="btn btn-info btn-submit" type="submit"><span class="mdi mdi-filter"></span></button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <canvas id="cash-flow-summary" height="100"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="clearfix">
                                        <div class="pull-left"></div>
                                        <div class="pull-right">
                                            <a href="{{ route('finance.account.export', ['account' => $account, 'type' => 'pdf']) }}&from={{ $from->toDateString() }}&to={{ $to->toDateString() }}" class="btn btn-pdf">
                                                PDF
                                            </a>
                                            <a href="{{ route('finance.account.export', ['account' => $account, 'type' => 'excel']) }}&from={{ $from->toDateString() }}&to={{ $to->toDateString() }}"
                                               class="btn btn-excel">
                                                Excel
                                            </a>
                                        </div>
                                    </div>
                                    <br />
                                    <h3><b>RELATED TRANSACTIONS</b> <span class="pull-right">Total: {{ count($account->transactions) }}</span></h3>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table color-table muted-table table-scroll">
                                            <thead>
                                                <tr>
                                                    <th width="12%">DATE</th>
                                                    <th style="padding-left: 35px !important;">DESCRIPTION</th>
                                                    <th class="text-right" width="15%">DEBIT</th>
                                                    <th class="text-right" width="15%">CREDIT</th>
                                                    <th class="text-right" width="15%">BALANCE</th>
                                                </tr>
                                                <tr style="background-color: #ecf0f3;">
                                                    <td colspan="2">
                                                        <b>Starting Balance</b>
                                                    </td>
                                                    <td class="text-right" width="15%">
                                                        @if($runningBalance['intBalType'] == 'Debit')
                                                            <b>{{ number_format($runningBalance['intBalView'], 2) }}</b>
                                                        @endif
                                                    </td>
                                                    <td class="text-right" width="15%">
                                                        @if($runningBalance['intBalType'] == 'Credit')
                                                            <b>{{ number_format($runningBalance['intBalView'], 2) }}</b>
                                                        @endif
                                                    </td>
                                                    <td class="text-right" width="15%"><b>{{ number_format($runningBalance['intBalView'], 2) }}</b></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($runningBalance['trans'])
                                                    @foreach($runningBalance['trans'] as $tranKey => $tran)
                                                        <tr>
                                                            <td width="12%">{{ carbon($tran->date)->format('F j, Y') }}</td>
                                                            <td>
                                                                {{ $tran->transaction->auto_narration or 'None' }} <br />
                                                                <a target="_blank" href="{{ route('finance.trans.show', $tran->transaction) }}">View Journal</a>
                                                            </td>
                                                            <td class="text-right" width="15%">{{ $tran->type == 'Debit' ? number_format($tran->amount, 2) : '' }}</td>
                                                            <td class="text-right" width="15%">{{ $tran->type == 'Credit' ? number_format($tran->amount, 2) : '' }}</td>
                                                            <td class="text-right" width="15%">{{ number_format($tran->balanceView, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5">No Transactions Founds...</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr style="background-color: #ecf0f3;">
                                                    <td colspan="2">
                                                        <b>Totals and Ending Balance</b>
                                                    </td>
                                                    <td class="text-right" width="15%"><b>{{ number_format($runningBalance['debitBal'], 2) }}</b></td>
                                                    <td class="text-right" width="15%"><b>{{ number_format($runningBalance['creditBal'], 2) }}</b></td>
                                                    <td class="text-right" width="15%"><b>{{ number_format($runningBalance['endBal'], 2) }}</b></td>
                                                </tr>
                                                {{--<tr style="background-color: #e0e7eb;">
                                                    <td colspan="4">
                                                        <b>Balance Change</b><br />
                                                        <small>Difference between starting and ending balances</small>
                                                    </td>
                                                    <td class="text-right" width="15%">
                                                        <b>{{ number_format(abs($runningBalance['intBal'] - $runningBalance['endBal']), 2) }}</b>
                                                    </td>
                                                </tr>--}}
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>
                            </div>

                            <!-- list of opening balance references -->
                            @include('finance.account.opening.references')

                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span class="pull-right">Total: {{ count($account->documents) }}</span></h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $account])
                                </div>
                            </div>
                        </div>

                        <!-- recent logs -->
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="round align-self-center round-primary"><i class="ti-money"></i></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0">{{ number_format(accBalance($account)['balance'], 2) }}</h3>
                                            <h6 class="text-muted m-b-0">
                                                Account balance as at {{ carbon()->now()->format('F j, Y') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($account->opening_balance)
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="round align-self-center round-info"><i class="ti-money"></i></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0">
                                                {{ $account->opening_balance ? number_format($account->opening_balance, 2) : 'None' }}
                                                <small>({{ $account->opening_balance_type }})</small>
                                            </h3>
                                            <h6 class="text-muted m-b-0">
                                                Opening balance as at {{ $account->opening_balance_at ? carbon($account->opening_balance_at)->format('F j, Y') : '' }}
                                            </h6>
                                            <a class="go-ref text-success" style="cursor: pointer;"><small>Click here to view references</small></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $account])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $account, 'modelName' => 'Account'])
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
    @include('general.comment.script', ['modelId' => $account->id])
    @include('_inc.document.script', ['model' => $account])
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        app.controller('AccountController', ['$scope', '$http', function ($scope, $http) {
            $scope.references = @json($account->references);
        }]);

        $('document').ready(function () {
            var data = @json($runningBalance['chart']);
            var ctx = document.getElementById("cash-flow-summary").getContext('2d');
            var stackedLine = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Cash',
                        data: data.data,
                        backgroundColor: '#00b3a146',
                        borderColor: '#00897b',
                        borderWidth: 1,
                        fill: false,
                    }]
                },
                options: {
                    responsive: true,
                }
            });

            $('.go-ref').click(function () {
                $('html, body').animate({
                    scrollTop: $(".opening-card").offset().top - 150
                }, 2000);
            });

            $('.input-daterange input').each(function() {
                $('.input-daterange').datepicker({});
            });
            
            $('.btn-submit').click(function () {
                $('#fiter-form').submit();
            });
        });
    </script>
@endsection