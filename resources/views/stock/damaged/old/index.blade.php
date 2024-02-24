@extends('layouts.master')
@section('title', 'Damaged Stocks')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row" ng-controller="DamagedStockController">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <h3 class="card-title"><i class="ti-truck"></i> Damaged Stocks</h3>
                </div>
                <hr>
                <div class="card-body">
                    <!-- from to filter -->
                    <div class="form-filter">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group required @{{ hasError('store') ? 'has-danger' : '' }}">
                                    <label class="control-label">Company</label>
                                    <div class="ui fluid  search selection dropdown company-drop-down @{{ hasError('company') ? 'error' : '' }}">
                                        <input type="hidden" name="vehicle">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a company</div>
                                        <div class="menu">
                                            @foreach(companyDropDown() as $key => $company)
                                                <div class="item" data-value="{{ $key }}">{{ $company }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">@{{ hasError('company') ? hasError('company') : ''
                                        }}</p>
                                </div>
                            </div>
                        </div>
                        @include('report.general.date.index')
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
                        <div class="pull-right"></div>
                    </div>
                    <hr class="hr-dark">

                    <!-- heading section -->
                    <div class="heading-section">
                        <h2 class="text-center"><b>Damaged Stocks</b></h2>
                        <p class="text-center text-muted"><b>Available stocks as at </b> @{{ date | date }}</p>
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
                            <p>Please wait, stock lists loading...</p>
                        </div>
                    </div>

                    <div class="orders-list" ng-hide="loading">
                        <div class="row" ng-show="company">
                            <div class="col-md-12">
                                <div class="ribbon-wrapper card">
                                    <div class="ribbon ribbon-default">@{{ company.name }}</div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="cardScroll">
                                                <table class="table table-scroll">
                                                    <thead>
                                                    <tr>
                                                        <th class="table-active">PRODUCTS</th>
                                                        <th class="text-center table-danger" width="15%">DAMAGED QTY</th>
                                                        {{--<th width="50%">DAMAGED DETAILS</th>--}}
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr ng-repeat="item in items">
                                                        <td>@{{ item.name }} (@{{ item.code }})</td>
                                                        <td class="text-center" width="15%"><code style="font-size: 16px;">@{{ item.damagedQty ? item.damagedQty : 0 }}</code></td>
                                                        {{--<td width="50%">
                                                            <table ng-show="item.damagedItems">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Allocation details</th>
                                                                        <th>Qty</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr ng-repeat="damaged in item.damagedItems">
                                                                        <td>
                                                                            @{{ damaged.daily_sale.code }}
                                                                        </td>
                                                                        <td>
                                                                            @{{ damaged.damaged_qty }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>--}}
                                                    </tr>
                                                    <tr ng-show="!items">
                                                        <td colspan="10"><code>No damaged stock available in this company!</code></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" ng-show="!company && !loading">
                            <div class="col-md-12">
                                <span class="text-muted">Please choose a company to generate the damaged stock report</span>
                            </div>
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
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    @include('report.general.date.script')
    @include('general.helpers')
    <script>
        app.controller('DamagedStockController', ['$scope', '$http', function ($scope, $http) {
            var url = '{{ route('stock.damaged.index') }}';
            $scope.main = [];

            $scope.company = null;

            $scope.query = {
                date: new Date(),
                companyId: ''
            };

            $scope.el = {
                loader: $('.cus-create-preloader')
            };

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);

            $('.company-drop-down').dropdown({
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.query.companyId = val;
                }
            });

            $scope.generate = function () {
                $scope.loading = true;
                $http.get(url + '?' + $.param($scope.query)).then(function (response) {
                    $scope.main = response.data;
                    $scope.items = $scope.main.items;
                    $scope.company = $scope.main.company;
                    $scope.loading = false;
                    $scope.initScroll();
                });
            };

            $scope.initScroll = function () {
                $('.cardScroll').slimScroll({
                    height: '500px'
                });
            }

            // Reset Filters
            $scope.resetFilters = function () {
                dateRangeDropDown($scope);
                $('.company-drop-down').dropdown('clear');
                $scope.query.companyId = null;
                $scope.generate();
            };
        }
        ]).directive('cardDirective', function () {
            return function (scope, element, attrs) {
                scope.searchingObject[scope.comany.id] = false;
                $('.cardScroll').slimScroll({
                    height: '500px'
                });
            };
        });
    </script>
@endsection