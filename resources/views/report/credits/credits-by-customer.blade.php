@extends('layouts.master')
@section('title', 'Credits by Customer')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Reports') !!}
@endsection
@section('content')
    <section ng-controller="CreditSummaryController">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-b-0">
                        <h3 class="card-title"><i class="ti-bar-chart"></i> Credits by Customer</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <!-- from to filter -->
                        <div class="form-filter">
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
                            <h2 class="text-center"><b>Credits by Customer</b></h2>
                            <p class="text-center text-muted"><b>As at </b> @{{ date | date }}</p>
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
                        <div class="orders-list" ng-hide="loading">
                            <table class="table-nested table color-table muted-table table-bordered">
                                <thead>
                                <tr>
                                    <th>DETAILS</th>
                                    <th width="10%" class="text-center"></th>
                                    <th width="10%" class="text-right">TOTAL SALES</th>
                                    <th width="10%" class="text-right">TOTAL CASH</th>
                                    <th width="10%" class="text-right">TOTAL CHEQUE</th>
                                    <th width="10%" class="text-right">TOTAL DEPOSIT</th>
                                    <th width="10%" class="text-right">TOTAL CARD</th>
                                    <th width="10%" class="text-right">TOTAL RECEIVED</th>
                                    <th width="10%" class="text-right">TOTAL BALANCE</th>
                                </tr>
                                </thead>
                                <tbody ng-if="orders.length == 0">
                                    <tr class="table-danger">
                                        <td colspan="9" class="child-row-table">
                                            <p class="pl-3 no-data-info text-danger">
                                                <code>There are no records found.</code>
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody ng-class="{opened: order.opened}" ng-include="&#39;/template/tableTree.tpl.html&#39;" ng-repeat="order in orders"></tbody>
                            </table>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="order-sidebar" class="card card-outline-inverse disabled-dev" style="border: none !important;">
            <div class="order-preloader">
                <svg class="circular" viewBox="25 25 50 50">
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                </svg>
            </div>
            <div class="card-header ">
                <h3 class="m-b-0 text-white">Sales List</h3>
                <h6 class="card-subtitle text-white">Total - <b>@{{ orderListTotal.total_sales | number:2 }}</b></h6>
            </div>
            <div class="card-body" id="order-sidebar-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="ui celled structured table collapse-table">
                            <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Order no</th>
                                <th>Order date & time</th>
                                <th class="text-center">Cash/Credit</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right">Cash</th>
                                <th class="text-right">Cheque</th>
                                <th class="text-right">Deposit</th>
                                <th class="text-right">Card</th>
                                <th class="text-right">Received</th>
                                <th class="text-right">Balance</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="orderItem in orderList" ng-if="orderList.length">
                                    <td><a target="_blank" href="@{{ getCustomerShowUrl(orderItem.customer_id) }}">@{{ orderItem.customer_name }}</a></td>
                                    <td><a target="_blank" href="@{{ getOrderShowUrl(orderItem.id) }}">@{{ orderItem.ref }}</a></td>
                                    <td>@{{ orderItem.order_date }}</td>
                                    <td class="text-center @{{ orderItem.is_credit_sales == 'Cash' ? 'text-green' : 'text-danger' }}">@{{ orderItem.is_credit_sales }}</td>
                                    <td class="text-right">@{{ orderItem.total_sales | number:2 }}</td>
                                    <td class="text-right">@{{ orderItem.total_cash | number:2 }}</td>
                                    <td class="text-right">@{{ orderItem.total_cheque | number:2 }}</td>
                                    <td class="text-right">@{{ orderItem.total_deposit | number:2 }}</td>
                                    <td class="text-right">@{{ orderItem.total_card | number:2 }}</td>
                                    <td class="text-right">@{{ orderItem.total_paid | number:2 }}</td>
                                    <td class="text-right">@{{ orderItem.total_balance | number:2 }}</td>
                                </tr>
                                <tr ng-if="orderList.length">
                                    <td colspan="4" class="table-info text-right"><b>TOTAL</b></td>
                                    <td class="text-right table-success"><b>@{{ orderListTotal.total_sales | number:2 }}</b></td>
                                    <td class="text-right table-success"><b>@{{ orderListTotal.total_cash | number:2 }}</b></td>
                                    <td class="text-right table-success"><b>@{{ orderListTotal.total_cheque | number:2 }}</b></td>
                                    <td class="text-right table-success"><b>@{{ orderListTotal.total_deposit | number:2 }}</b></td>
                                    <td class="text-right table-success"><b>@{{ orderListTotal.total_card | number:2 }}</b></td>
                                    <td class="text-right table-success"><b>@{{ orderListTotal.total_paid | number:2 }}</b></td>
                                    <td class="text-right table-success"><b>@{{ orderListTotal.total_balance | number:2 }}</b></td>
                                </tr>
                                <tr ng-if="!orderList.length">
                                    <td colspan="11" class="text-center text-danger">
                                        <small>No records found.</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="products-sidebar" class="card card-outline-inverse disabled-dev" style="border: none !important;">
            <div class="products-preloader">
                <svg class="circular" viewBox="25 25 50 50">
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                </svg>
            </div>
            <div class="card-header ">
                <h3 class="m-b-0 text-white">Sold Items</h3>
            </div>
            <div class="card-body" id="products-sidebar-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="ui celled structured table collapse-table">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="product in products">
                                <td><a target="_blank" href="@{{ getProductShowUrl(product.id) }}">@{{ product.name }}</a></td>
                                <td class="text-center">@{{ product.quantity }}</td>
                                <td class="text-right">@{{ product.amount | number:2 }}</td>
                            </tr>
                            <tr>
                                <td class="text-right"><b>TOTAL</b></td>
                                <td class="text-center table-info"><b>@{{ soldItemsTotal.total_qty }}</b></td>
                                <td class="text-right table-success"><b>@{{ soldItemsTotal.total_sold | number:2 }}</b></td>
                            </tr>
                            <tr ng-if="products.length == 0">
                                <td colspan="11" class="text-center text-danger">
                                    <small>No records found.</small>
                                </td>
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
    @include('report.general.date.script')
    <script>
        app.controller('CreditSummaryController', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
            $scope.query = {
                date: new Date()
            };

            $scope.loading = true;
            $scope.orders = [];
            $scope.daterangeDD = $('.date-range');
            $scope.daterangeValue = '';

            $scope.el = {
                companyDropDown : $('.company-dropdown'),
                sidebar : $('#order-sidebar'),
                'loader' : $('.order-preloader'),
                productSidebar : $('#products-sidebar'),
                productLoader : $('.products-preloader')
            };

            //Initiate Date Range Drop down
            dateRangeDropDown($scope);

            // Generate Data using filters
            $scope.generate = function () {
                $scope.loading = true;
                var orderRoute = '{{ route('report.credits.by.customer') }}';
                $http.get(orderRoute + '?' + $.param($scope.query)).then(function (response) {
                    $scope.orders = response.data;
                    $scope.loading = false;
                })
            };
            $scope.generate();

            // Reset Filters
            $scope.resetFilters = function () {
                dateRangeDropDown($scope);
                $scope.generate();
            };

            // When click the add button open the model
            $scope.orderSlider = $scope.el.sidebar.slideReveal({
                position: "right",
                width: '85%',
                push: false,
                overlay: true,
                shown: function(slider, trigger){
                    // init scroll for side bar body
                    $('#order-sidebar-body').slimScroll({
                        color: 'gray',
                        height: '100%',
                        railVisible: true,
                        alwaysVisible: false
                    });
                },
                show: function(slider, trigger){
                    // $scope.hideLoader();
                }
            });

            // close side bar
            $scope.closeSideBar = function(){
                $scope.orderSlider.slideReveal("toggle");
            };

            // open side bar
            $scope.openSideBar = function(){
                $scope.orderSlider.slideReveal("toggle");
            };

            $scope.showLoader = function(){
                $scope.el.loader.addClass('loading');
                $scope.el.loader.removeClass('hidden');
            };

            // hide loading
            $scope.hideLoader = function(){
                $scope.el.loader.removeClass('loading');
                $scope.el.loader.addClass('hidden');
            };

            $scope.productSidebar = $scope.el.productSidebar.slideReveal({
                position: "right",
                width: '700px',
                push: false,
                overlay: true,
                shown: function(slider, trigger){
                    // init scroll for side bar body
                    $('#products-sidebar-body').slimScroll({
                        color: 'gray',
                        height: '100%',
                        railVisible: true,
                        alwaysVisible: false
                    });
                },
                show: function(slider, trigger){
                    // $scope.hideLoader();
                }
            });

            // close side bar
            $scope.closeProductSideBar = function(){
                $scope.productSidebar.slideReveal("toggle");
            };

            // open side bar
            $scope.openProductSideBar = function(){
                $scope.productSidebar.slideReveal("toggle");
            };

            $scope.showProductLoader = function(){
                $scope.el.productLoader.addClass('loading');
                $scope.el.productLoader.removeClass('hidden');
            };

            // hide loading
            $scope.hideProductLoader = function(){
                $scope.el.productLoader.removeClass('loading');
                $scope.el.productLoader.addClass('hidden');
            };


            $scope.getProductShowUrl = function(id)
            {
                var url = '{{ route('setting.product.show', ['product' => 'PRODUCT']) }}';
                return url.replace('PRODUCT', id);
            };

            $scope.showProducts = function(order) {
                $scope.products = [];
                var query = order.query;
                query = $.extend(query,  $scope.query);
                $scope.openProductSideBar();
                $scope.showProductLoader();
                var productListRoute = '{{ route('report.credits.sold.items.list') }}';
                $http.post(productListRoute, query).then(function (response) {
                    $scope.products = response.data;
                    $scope.calculateSoldItemsTotal();
                    $scope.hideProductLoader();
                })
            };

            $scope.getCustomerShowUrl = function(id)
            {
                var url = '{{ route('sales.customer.show', ['customer' => 'CUSTOMER']) }}';
                return url.replace('CUSTOMER', id);
            };

            $scope.getOrderShowUrl = function(id)
            {
                var url = '{{ route('sales.order.show', ['order' => 'ORDER']) }}';
                return url.replace('ORDER', id);
            };

            $scope.sum = function(obj, key){
                return _.reduce(obj, function(memo, item){
                    return memo + item[key];
                }, 0)
            };

            $scope.calculateSalesListTotal = function(){
                $scope.orderListTotal = {
                    total_sales : $scope.sum($scope.orderList, 'total_sales'),
                    total_paid : $scope.sum($scope.orderList, 'total_paid'),
                    total_cheque : $scope.sum($scope.orderList, 'total_cheque'),
                    total_cash : $scope.sum($scope.orderList, 'total_cash'),
                    total_deposit : $scope.sum($scope.orderList, 'total_deposit'),
                    total_card : $scope.sum($scope.orderList, 'total_card'),
                    total_balance : $scope.sum($scope.orderList, 'total_balance')
                };
            };

            $scope.calculateSoldItemsTotal = function(){
                $scope.soldItemsTotal = {
                    total_qty : $scope.sum($scope.products, 'quantity'),
                    total_sold : $scope.sum($scope.products, 'amount')
                };
            };

            $scope.showOrders = function(order) {
                $scope.orderList = [];
                var query = order.query;
                query = $.extend(query,  $scope.query);
                $scope.openSideBar();
                $scope.showLoader();
                var orderListRoute = '{{ route('report.credits.sales.list') }}';
                $http.post(orderListRoute, query).then(function (response) {
                    $scope.orderList = response.data;
                    $scope.calculateSalesListTotal();
                    $scope.hideLoader();
                })
            };
        }]);
    </script>
@endsection
