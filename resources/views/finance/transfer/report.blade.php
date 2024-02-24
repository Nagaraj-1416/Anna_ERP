@extends('layouts.master')
@section('title', 'Transfers Report')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row" ng-controller="TransferController">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h3 class="card-title"><i class="ti-receipt"></i> Transfers Report</h3>
                            <h6 class="card-subtitle">
                                A <code>Sender</code> & <code>Date range</code> filters are required to generate this day book!
                            </h6>
                        </div>
                        <div class="pull-right"></div>
                    </div>
                </div>
                <hr>
                <div class="card-body p-b-5">
                    <div class="form-filter">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group required @{{ hasError('company') ? 'has-danger' : '' }}">
                                    <label class="control-label">Sender</label>
                                    <div class="ui fluid  search selection dropdown company-drop-down @{{ hasError('company') ? 'error' : '' }}">
                                        <input type="hidden" name="company">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a sender</div>
                                        <div class="menu">
                                            @foreach(companyDropDown() as $key => $company)
                                                <div class="item" data-value="{{ $key }}">{{ $company }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">
                                        @{{ hasError('company') ? hasError('company') : ''}}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group required">
                                    <label class="control-label">Type of transfer</label>
                                    <div class="demo-radio-button">
                                        <input name="type" value="Cash" type="radio" class="with-gap" id="Cash" checked="">
                                        <label for="Cash">Cash</label>
                                        <input name="type" value="Cheque" type="radio" class="with-gap" id="Cheque">
                                        <label for="Cheque">Cheque</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group required">
                                    <label class="control-label">Mode of transfer</label>
                                    <div class="demo-radio-button">
                                        <input name="mode" value="ByHand" type="radio" class="with-gap" id="ByHand" checked="">
                                        <label for="ByHand">ByHand</label>
                                        <input name="mode" value="DepositedToBank" type="radio" class="with-gap" id="DepositedToBank">
                                        <label for="DepositedToBank">DepositedToBank</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('report.general.date-range')
                    </div>
                    <div class="clearfix m-t-10">
                        <div class="pull-left">
                            <button ng-click="generate()" class="btn btn-info"><i class="ti-filter"></i>
                                Generate
                            </button>
                            <button ng-click="resetFilters()" class="btn btn-inverse"><i class="ti-eraser"></i> Reset</button>
                        </div>
                        <div class="pull-right"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="loading" ng-show="loading">
                            <div class="loading-dot"></div>
                            <div class="loading-dot"></div>
                            <div class="loading-dot"></div>
                            <div class="loading-dot"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="loading" ng-show="loading">
                            <p>Please wait, day book is loading...</p>
                        </div>
                    </div>
                </div>

                <div class="card-body" ng-show="!loading">

                    <div class="row" ng-show="company">
                        <div class="col-md-12">
                            <div class="ribbon-wrapper card">
                                <div class="ribbon ribbon-default">
                                    @{{ company.name }}'s Day Book
                                    <code>From</code> @{{ fromRange | date }} <code>To</code> @{{ toRange | date }}
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="ui celled structured table collapse-table">
                                            <thead>
                                            <tr>
                                                <th>ACCOUNT</th>
                                                <th class="text-right" width="15%">DEBIT</th>
                                                <th class="text-right" width="15%">CREDIT</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr style="background-color: #e0e7eb;">
                                                <td colspan="3"><u>SALES</u></td>
                                            </tr>
                                            <tr>
                                                <td>Sales made for the period</td>
                                                <td class="text-right"></td>
                                                <td class="text-right">@{{ sales | number:2 }}</td>
                                            </tr>
                                            <tr style="background-color: #e0e7eb;">
                                                <td colspan="3"><u>PAYMENTS RECEIVED</u></td>
                                            </tr>

                                            <tr>
                                                <td>Cash received</td>
                                                <td class="text-right">@{{ cashReceived | number:2 }}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Cheque received</td>
                                                <td class="text-right">@{{ chequeReceived | number:2 }}</td>
                                                <td></td>
                                            </tr>
                                            <tr style="background-color: #e0e7eb;">
                                                <td colspan="3"><u>EXPENSES</u></td>
                                            </tr>
                                            <tr ng-repeat="expense in expenses">
                                                <td>
                                                    @{{ expense.type.name }}
                                                    <small>(@{{ expense.notes }} - by @{{ expense.prepared_by.name }})</small>
                                                </td>
                                                <td class="text-right">
                                                    @{{ expense.amount | number:2 }}
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right"><b>TOTAL EXPENSE</b></td>
                                                <td class="text-right"><b>(@{{ expensesTotal | number:2 }})</b></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right"><b>SHORTAGE</b></td>
                                                <td class="text-right"><b>(@{{ shortage | number:2 }})</b></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right"><b>EXCESS</b></td>
                                                <td class="text-right"><b>@{{ excess | number:2 }}</b></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right"><b>BALANCE CASH</b></td>
                                                <td class="text-right"><b>@{{ balCash | number:2 }}</b></td>
                                                <td class="text-right"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" ng-show="!company && !loading">
                        <div class="col-md-12">
                            <hr>
                            <span class="text-muted">Please choose company to generate the day book report</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    @include('report.general.date-range-script')
    @include('finance.transfer.script-report')
@endsection