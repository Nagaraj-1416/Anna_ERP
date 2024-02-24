@extends('layouts.master')
@section('title', 'Damages by Product')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="DamagedProductController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Damages by Product</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('company') ? 'has-danger' : '' }} required">
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

                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('product') ? 'has-danger' : '' }} required">
                                        <label class="control-label">Product</label>
                                        <div class="ui fluid  search selection dropdown product-drop-down {{ $errors->has('product') ? 'error' : '' }}">
                                            <input type="hidden" name="product">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a product</div>
                                            <div class="menu"></div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('product') }}</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('rep_id') ? 'has-danger' : '' }}">
                                        <label class="control-label">Damage reason</label>
                                        <div class="ui fluid  search selection dropdown reason-drop-down {{ $errors->has('rep_id') ? 'error' : '' }}">
                                            <input type="hidden" name="rep_id">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a reason</div>
                                            <div class="menu">
                                                <div class="item" data-value="{{ 'Product was expired' }}">Product was expired</div>
                                                <div class="item" data-value="{{ 'Product was damaged or defective' }}">Product was damaged or defective</div>
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('rep_id') }}</p>
                                    </div>
                                </div>
                            </div>
                            @include('report.general.date-range')
                        </div>
                        <div class="clearfix m-t-10">
                            <div class="pull-left">
                                <button ng-click="generate()"  class="btn btn-info"><i class="ti-filter"></i>
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
                            <h2 class="text-center"><b>Damages by Product</b></h2>
                            <p class="text-center text-muted"><b>From</b> @{{ fromDate | date}}
                                <b>To</b> @{{ toDate | date}}</p>
                        </div>

                        {{--data--}}
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

                        <div class="orders-list" ng-hide="loading">
                            <div class="table-responsive">
                                <table class="table color-table muted-table">
                                    <thead>
                                        <tr>
                                            <th width="20%">RETURN</th>
                                            <th width="35%">ORDER</th>
                                            <th width="10%" class="text-right">SOLD RATE</th>
                                            <th width="10%" class="text-right">RETURNED RATE</th>
                                            <th width="10%" class="text-center">DAMAGED QTY</th>
                                            <th width="15%" class="text-right">RETURNED AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="(key, returnItems) in items" ng-show="length">
                                            <td colspan="6" class="no-padding-tbl-cel">
                                                <a target="_blank"
                                                   href="{{ url('/') }}/sales/customer/@{{ getCustomer(key).id }}">
                                                    <b>@{{ getCustomer(key).display_name }}</b>
                                                </a> <br />
                                                <div>
                                                    <table class="table muted-table">
                                                        <tr ng-repeat="returnItem in returnItems" ng-show="length">
                                                            <td width="20%">
                                                                <a target="_blank" href="/sales/return/@{{ returnItem.sales_return.id }}">
                                                                    @{{ returnItem.sales_return.code }} (@{{ returnItem.sales_return.date | date }})
                                                                </a>
                                                            </td>
                                                            <td width="35%">
                                                                <a target="_blank" href="/sales/order/@{{ returnItem.order.id }}">
                                                                    @{{ returnItem.order.ref }} (@{{ returnItem.order.order_date | date }})
                                                                </a>
                                                            </td>
                                                            <td width="10%" class="text-right">@{{ returnItem.sold_rate | number:2 }}</td>
                                                            <td width="10%" class="text-right">@{{ returnItem.returned_rate | number:2 }}</td>
                                                            <td width="10%" class="text-center"><code style="font-size: 16px;">@{{ returnItem.qty }}</code></td>
                                                            <td width="15%" class="text-right">@{{ returnItem.returned_amount | number:2 }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr ng-show="length">
                                            <td colspan="4" class="text-right"><b>TOTAL</b></td>
                                            <td class="text-center"><b>@{{ totalQty }}</b></td>
                                            <td class="text-right"><b>@{{ totalAmount | number:2 }}</b></td>
                                        </tr>
                                        <tr ng-show="!length">
                                            <td>No data to display...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                        </div>
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
        app.controller('DamagedProductController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                fromDate: '',
                toDate: '',
                company: '',
                product: '',
                reason: ''
            };

            $scope.loading = true;

            $scope.products = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';
            $scope.dropdowns = {
                company: $('.company-drop-down'),
                product: $('.product-drop-down'),
                reason: $('.reason-drop-down')
            };

            $scope.dropdowns.company.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.company = val;
                }
            });

            $scope.dropdowns.product.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: '{{ route('setting.product.search', ['type' => 'Finished Good']) }}' + '/{query}',
                    cash: false
                },
                onChange: function (val, name) {
                    $scope.query.product = val;
                    $scope.productName = name;
                }
            });

            $scope.dropdowns.reason.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val, name) {
                    $scope.query.reason = val;
                }
            });

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);

            $scope.length = 0;

            // Generate Data using filters
            $scope.generate = function () {
                $scope.fromDate = $scope.query.fromDate;
                $scope.toDate = $scope.query.toDate;
                $scope.loading = true;
                var orderRoute = '{{ route('report.damage.by.product') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.items = response.data.items;
                    $scope.customers = response.data.customers;
                    $scope.totalQty = response.data.totalQty;
                    $scope.totalAmount = response.data.totalAmount;
                    $scope.loading = false;
                    $scope.length = _.toArray($scope.items).length;
                })
            };
            $scope.generate();

            // Reset Filters
            $scope.resetFilters = function () {
                $scope.query = {
                    fromDate: '',
                    toDate: '',
                    product: ''
                };
                $scope.daterangeDD.dropdown('clear');
                $scope.dropdowns.company.dropdown('clear');
                $scope.dropdowns.reason.dropdown('clear');
                $scope.dropdowns.product.dropdown('clear');
                $scope.daterangeDD.dropdown('set text', 'Today').dropdown('set value', 'today');
                $scope.generate();
            };

            $scope.sum = function (array) {
                var sum = _.reduce(array, function (memo, num) {
                    return memo + num;
                }, 0);
                return sum;
            };

            $scope.getOrderQtyTotal = function (product) {
                return $scope.sum(_.pluck(_.pluck(product.orders, 'pivot'), 'quantity'));
            };

            $scope.getCustomer = function (id) {
                var name = _.find($scope.customers, function (value, key) {
                    if (value.id === parseInt(id)) return value;
                });
                return name;
            };

            $scope.getExportRoute = function () {
                var route = '{{ route('report.damage.by.product.export') }}';
                return route + '?' + $.param($scope.query);
            };

        }]);
    </script>
@endsection
