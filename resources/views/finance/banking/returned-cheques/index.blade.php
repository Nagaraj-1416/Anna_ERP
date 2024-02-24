@extends('layouts.master')
@section('title', 'Returned Cheques')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
<section ng-controller="ReturnedChequeController">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h3 class="card-title"><i class="ti-receipt"></i> Returned Cheques</h3>
                            <h6 class="card-subtitle">
                                Cheques that are written
                                <b>from</b> @{{ fromDate | date}}
                                <b>to</b> @{{ toDate | date}}
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
                                <div class="form-group {{ $errors->has('company') ? 'has-danger' : '' }}">
                                    <label class="control-label">Company</label>
                                    <div class="ui fluid  search selection dropdown company-drop-down {{ $errors->has('company') ? 'error' : '' }}">
                                        <input type="hidden" name="product">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a company</div>
                                        <div class="menu">
                                            @foreach(companyDropDown() as $key => $company)
                                                <div class="item" data-value="{{ $key }}">{{ $company }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('company') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row m-b-15">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('route_id') ? 'has-danger' : '' }}">
                                    <label class="control-label">Route</label>
                                    <div class="ui fluid  search selection dropdown route-drop-down {{ $errors->has('route_id') ? 'error' : '' }}">
                                        <input type="hidden" name="route_id">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a route</div>
                                        <div class="menu">
                                            @foreach(routeDropDown() as $key => $route)
                                                <div class="item" data-value="{{ $key }}">{{ $route }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('route_id') }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Customer</label>
                                    <div class="ui fluid  search selection dropdown customer-drop-down">
                                        <input type="hidden" name="customer">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a customer</div>
                                        <div class="menu"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Cheque type</label>
                                    <div class="ui fluid  search selection dropdown cheque-type-drop-down">
                                        <input type="hidden" name="cheque_type">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a type</div>
                                        <div class="menu">
                                            @foreach(chequeTypeDD() as $key => $type)
                                                <div class="item" data-value="{{ $key }}">{{ $type }}</div>
                                            @endforeach
                                        </div>
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
                            <button ng-click="resetFilters()" class="btn btn-inverse"><i class="ti-eraser"></i>
                                Reset
                            </button>
                        </div>
                        <div class="pull-right">
                            {{--<a href="#" class="btn waves-effect waves-light btn-inverse"><i class="fa fa-file-pdf-o"></i> Export to PDF</a>--}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="loading" ng-show="loading">
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="loading" ng-show="loading">
                        <p>Please wait report is generating</p>
                    </div>
                </div>

                <div class="card-body" ng-hide="loading">
                    <table class="ui celled structured table collapse-table">
                        <thead>
                            <tr>
                                <th width="25%">CHEQUE DETAILS</th>
                                <th width="10%">STATUS & BOUNCED DATE</th>
                                <th>REFERENCES</th>
                                <th class="text-right" width="10%">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(key, chequesData) in cheques" ng-show="length">
                                <td width="15%">
                                    <b>Cheque#</b> <code><b>@{{ getChequeId(key) }}</b></code><br />
                                    <span class="text-warning">@{{ getChequeDate(chequesData) | date }}</span>,
                                    <span class="text-info">@{{ getBank(chequesData) }}</span> <br /><br />
                                    <a ng-show="getChequeStatus(chequesData) == 'Bounced'" target="_blank" href="/finance/returned-cheque/@{{ key }}/show" class="btn btn-xs btn-info">View Details</a>
                                    <a ng-show="getChequeStatus(chequesData) == 'Bounced'" target="_blank" href="/finance/returned-cheque/@{{ key }}/payment/create" class="btn btn-xs btn-success">Make Payments</a>
                                </td>
                                <td>
                                    <span ng-class="getChequeStatus(chequesData) == 'Not Realised' ? 'text-info' : '' ||
                                        getChequeStatus(chequesData) == 'Realised' ? 'text-green' : '' ||
                                        getChequeStatus(chequesData) == 'Bounced' ? 'text-danger' : '' ||
                                        getChequeStatus(chequesData) == 'Deposited' ? 'text-warning' : '' ">
                                        @{{ getChequeStatus(chequesData) }}
                                    </span>
                                    <br />
                                    @{{ getBouncedDate(chequesData) }}
                                </td>
                                <td>
                                    <table ng-if="chequesData" width="100%" class="table custom-table">
                                        <tbody>
                                            <tr ng-repeat="cheque in chequesData">
                                                <td width="85%">
                                                    <a target="_blank" href="/sales/customer/@{{ cheque.chequeable.invoice.customer.id }}">
                                                        @{{ cheque.chequeable.invoice.customer.display_name }}
                                                    </a><br />
                                                    <a target="_blank" href="/sales/invoice/@{{ cheque.chequeable.invoice_id }}">
                                                        @{{ cheque.chequeable.invoice.ref }} (@{{ cheque.chequeable.invoice.invoice_date | date }})
                                                    </a>
                                                </td>
                                                <td class="text-right" style="border-right: none;">@{{ cheque.chequeable.payment | number:2 }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td class="text-right" style="vertical-align: bottom; border-bottom: #191926 3px solid;">
                                    <b>@{{ getChequeTotal(chequesData) | number:2 }}</b>
                                </td>
                            </tr>
                            <tr style="font-size: 16px;">
                                <td colspan="3" class="text-right"><b>TOTAL</b></td>
                                <td class="text-right"><b>@{{ chequesTotal | number:2 }}</b></td>
                            </tr>
                            <tr ng-show="!length">
                                <td colspan="4">No cheques to display...</td>
                            </tr>
                        </tbody>
                    </table>
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
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    @include('report.general.date-range-script')
    <script>
        app.controller('ReturnedChequeController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                fromDate: '',
                toDate: '',
                chequeType: '',
                company: '',
                route: '',
                customer: ''
            };

            $scope.loading = true;

            $scope.cheques = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
            $scope.dropdowns = {
                chequeType: $('.cheque-type-drop-down'),
                company: $('.company-drop-down'),
                route: $('.route-drop-down'),
                customer: $('.customer-drop-down')
            };

            $scope.dropdowns.company.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.company = val;
                    $scope.dropdowns.route.dropdown('clear');
                    routeDropDown(val);
                }
            });

            // Sales Rep Drop Down
            $scope.dropdowns.chequeType.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.chequeType = val;
                }
            });

            function routeDropDown(company) {
                var url = '{{ route('setting.route.by.company.search', ['companyId']) }}';
                url = url.replace('companyId', company);
                $scope.dropdowns.route.dropdown('setting', {
                    forceSelection: false,
                    apiSettings: {
                        url: url + '/{query}',
                        cache:false,
                    },
                    saveRemoteData:false,
                    onChange: function(val, name){
                        $scope.query.route = val;
                        $scope.dropdowns.customer.dropdown('clear');
                        customerDropDown(val);
                    }
                });
            }

            function customerDropDown(route) {
                var url = '{{ route('sales.customer.by.route.search', ['routeId']) }}';
                url = url.replace('routeId', route);
                $scope.dropdowns.customer.dropdown('setting', {
                    forceSelection: false,
                    apiSettings: {
                        url: url + '/{query}',
                        cache:false,
                    },
                    saveRemoteData:false,
                    onChange: function(val, name){
                        $scope.query.customer = val;
                    }
                });
            }

            // initiate date range drop-down
            dateRangeDropDown($scope);

            $scope.length = 0;

            // generate report using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var orderRoute = '{{ route('finance.return.cheques.index') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.cheques = response.data.cheques;
                    $scope.chequesTotal = response.data.chequesTotal;
                    $scope.loading = false;
                    $scope.length = _.toArray($scope.cheques).length;
                })
            };
            $scope.generate();

            // reset filters
            $scope.resetFilters = function () {
                $scope.query = {
                    fromDate: '',
                    toDate: ''
                };
                $scope.daterangeDD.dropdown('clear');
                $scope.daterangeDD.dropdown('set text', 'Today').dropdown('set value', 'today');
                $scope.dropdowns.chequeType.dropdown('clear');
                $scope.dropdowns.company.dropdown('clear');
                $scope.dropdowns.route.dropdown('clear');
                $scope.dropdowns.customer.dropdown('clear');
                $scope.generate();
            };

            $scope.sum = function (array) {
                var sum = _.reduce(array, function (memo, num) {
                    return memo + num;
                }, 0);
                return sum;
            };

            $scope.getChequeTotal = function (cheque) {
                var amounts = _.pluck(cheque, 'amount');
                return $scope.sum(amounts);
            };

            $scope.getChequeDate = function (cheque) {
                return _.first(cheque).cheque_date;
            };

            $scope.getChequeStatus = function (cheque) {
                return _.first(cheque).status;
            };

            $scope.getBouncedDate = function (cheque) {
                return _.first(cheque).bounced_date;
            };

            $scope.getTransferStatus = function (cheque) {
                return _.first(cheque).is_transferred;
            };

            $scope.getBank = function (cheque) {
                return _.first(cheque).bank.name;
            };

            $scope.getChequeId = function (key) {
                return key.split('___')[0];
            };

        }]);
    </script>
@endsection


