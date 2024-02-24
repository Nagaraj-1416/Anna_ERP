@extends('layouts.master')
@section('title', 'Add Payment')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Expense') !!}
@endsection
@section('content')
    <section>
        <div class="row" ng-controller="TransferController">
            <div class="col-12">
                <div class="card card-outline-primary">
                    <div class="card-header">
                        <h3 class="text-white">Payment Details</h3>
                    </div>
                    <div class="card-body">
                        {!! form()->model($expense, ['url' => route('expense.receipt.store.payment', [$expense, $mode]), 'method' => 'POST']) !!}
                        <div class="row">
                            <div class="col-md-3">
                                {!! form()->bsText('payment', 'Payment', null, ['placeholder' => 'payment', 'class' => 'form-control expense-amount']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row m-t-10 cih-panel">
                                    <div class="col-md-12">
                                        <h6 class="box-title"><b>Cheques in Hand</b></h6>
                                        <hr>
                                        <table class="ui structured table collapse-table">
                                            <thead>
                                            <tr>
                                                <th style="width: 1%;"></th>
                                                <th style="width: 32%;">CHEQUE DETAILS</th>
                                                <th style="width: 47%;">REFERENCES</th>
                                                <th class="text-right" style="width: 20%;">TOTAL</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($availableCheques as $chequeKey => $cheques)
                                                @php
                                                    $chequeNo = explode('___', $chequeKey)[0];
                                                    $chequeData = getChequeDataByNo($cheques->first());
                                                @endphp
                                                <tr>
                                                    <td style="width: 1%;">
                                                        <div class="demo-checkbox">
                                                            <input type="checkbox" id="{{ 'md_checkbox_29_' . $chequeKey }}"
                                                                   name="cheques[]"
                                                                   class="chk-col-cyan" value="{{ $chequeKey }}" data-id="{{ $chequeKey }}" data-value="{{ $chequeData['eachTotal'] }}">
                                                            <label for="{{ 'md_checkbox_29_' . $chequeKey }}"></label>
                                                        </div>
                                                    </td>
                                                    <td style="width: 32%;">
                                                        <b>Cheque# </b><code style="font-size: 14px;">{{ $chequeNo }}</code><br />
                                                        <span class="text-warning">{{ $chequeData['date'] }}</span>,
                                                        <span class="text-info">{{ $chequeData['bank'] }}</span>
                                                    </td>
                                                    <td style="width: 47%;">
                                                        @if($cheques)
                                                            @foreach($cheques as $cheque)
                                                                <div class="clearfix">
                                                                    <div class="pull-left">
                                                                        <a target="_blank" href="/sales/customer/{{ $cheque->customer->id }}">
                                                                            {{ $cheque->customer->display_name }}
                                                                        </a><br />

                                                                    </div>
                                                                    <div class="pull-right">
                                                                        {{ number_format($cheque->amount, 2) }}
                                                                    </div>
                                                                </div>
                                                                <br />
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td class="text-right" style="vertical-align: bottom; width: 20%;"><b>{{ number_format($chequeData['eachTotal'], 2) }}</b></td>
                                                </tr>
                                            @endforeach
                                            <tr style="font-size: 16px;">
                                                <td class="text-right" colspan="3"><b>TOTAL</b></td>
                                                <td class="text-right" style="width: 15%;"><b>{{ number_format($grandTotal, 2) }}</b></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                {!! form()->bsTextarea('notes', 'Remarks', null, ['placeholder' => 'enter payment related remarks here...', 'cols' => 100, 'rows' => 3]) !!}
                            </div>
                        </div>
                        <hr>
                        {!! form()->bsSubmit('Add Payment', 'btn btn-success waves-effect waves-light m-r-10') !!}
                        {!! form()->bsCancel('Cancel', 'expense.receipt.show', $expense) !!}
                        {{ form()->close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        app.controller('TransferController', ['$scope', '$http', function ($scope, $http) {
            $scope.today = '{{ carbon()->toDateTimeString() }}';

            $scope.payMode = '{{ $mode }}';

            $scope.formElement = {
                transferAmount: $('.expense-amount'),
                chkCheque: $('.chk-col-cyan')
            };

            /*if($scope.payMode === 'ThirdPartyCheque'){
                $scope.formElement.transferAmount.attr("readonly", "readonly");
            }*/

            $scope.formElement.chkCheque.change(function (e) {
                e.preventDefault();
                if($(this).is(":checked")) {
                    var addedTransAmount = $scope.formElement.transferAmount.val();
                    var addedChequeAmount = $(this).data('value');
                    var addedAmount = Number(addedTransAmount) + Number(addedChequeAmount);
                    $scope.formElement.transferAmount.val(addedAmount)
                }else{
                    var deductedTransAmount = $scope.formElement.transferAmount.val();
                    var deductedChequeAmount = $(this).data('value');
                    var deductedAmount = Number(deductedTransAmount) - Number(deductedChequeAmount);
                    $scope.formElement.transferAmount.val(deductedAmount)
                }
            });
        }]);
    </script>
@endsection