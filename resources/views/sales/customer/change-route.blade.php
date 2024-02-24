@extends('layouts.master')
@section('title', 'Edit Customer')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="CustomerRouteChangeController">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <div class="pull-left">
                        <h4 class="text-white">Change Customers' Routes & Locations</h4>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-primary btn-sm @{{ prevPageDisabled() }}" ng-click="prevPage()">
                            Previous
                        </button>
                        <span class="btn btn-warning btn-sm">@{{ (query.page) }}</span>
                        <button class="btn btn-info btn-sm @{{ nextPageDisabled() }}" ng-click="nextPage()">Next
                        </button>
                    </div>
                </div>
                <div class="row m-t-10" ng-show="loading">
                    <div class="loading">
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                    </div>
                </div>
                <div class="row" ng-show="loading">
                    <div class="loading">
                        <p>loading customers</p>
                    </div>
                </div>


                <div class="card-body" ng-show="!loading">
                    <div class="m-b-10 m-r-10 ">
                        <input type="text" id="demo-input-search2" ng-model="searchCustomer"
                               placeholder="search for customer here" class="form-control">
                    </div>
                    <table class="ui striped celled table">
                        <thead>
                        <tr>
                            <th>Customer details</th>
                            <th width="25%">Route</th>
                            <th width="20%">Location</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="(key, customer) in pagination.data | filter:searchCustomer" customer-directive>
                            <td>
                                @{{ customer.display_name }}<br/>
                                <span class="text-muted">@{{ customer.tamil_name }}</span>
                            </td>
                            <td>
                                <div class="form-group required"
                                     ng-class="hasErrors('customers.' + key + '.changedRouteId')  ? ' has-danger' : ''">
                                    <div class="ui fluid normal search selection dropdown route-drop-down"
                                         ng-class="hasErrors('customers.' + key + '.changedRouteId')  ? ' error' : ''">
                                        @if(isset($customer))
                                            <input name="route_id" type="hidden"
                                                   value="{{ old('_token') ? old('route_id'): $customer->route_id }}">
                                        @else
                                            <input name="route_id" type="hidden"
                                                   value="{{ old('_token') ? old('route_id'): '' }}">
                                        @endif
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
                            </td>
                            <td>
                                <div class="form-group required"
                                     ng-class="hasErrors('customers.' + key + '.changedRouteLocationId')  ? ' has-danger' : ''">
                                    <div class="ui fluid normal search selection dropdown location-drop-down"
                                         ng-class="hasErrors('customers.' + key + '.changedRouteLocationId')  ? ' error' : ''">
                                        @if(isset($customer))
                                            <input name="location_id" type="hidden"
                                                   value="{{ old('_token') ? old('location_id'): $customer->location_id }}">
                                        @else
                                            <input name="location_id" type="hidden"
                                                   value="{{ old('_token') ? old('location_id'): '' }}">
                                        @endif
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a location</div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('location_id') }}</p>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr/>
                    <div class="clearfix">
                        <div class="pull-left">
                            {!! form()->bsCancel('Cancel', 'sales.customer.index') !!}
                        </div>
                        <div class="pull-right">
                            <button class="btn btn-success" ng-click="updateRoutes()">
                                <i class="ti-check"></i> Update Route
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    <script>
        app.controller('CustomerRouteChangeController', ['$scope', '$http', function ($scope, $http) {
            $scope.query = {
                page: '{{ $page ?? 1 }}',
            };
            $scope.errors = {};
            $scope.pagination = {};
            $scope.loading = true;
            var getCustomer = '{{ route('sales.customer.change.route') }}';

            $scope.setStore = function (data) {
                localStorage.setItem('customers', JSON.stringify(data));
            };
            $scope.getDataFromStore = function (key) {
                return localStorage.getItem(key);
            };
            $scope.prevPage = function () {
                if ($scope.query.page > 1) {
                    $scope.query.page--;
                    $scope.paginationChanged()
                }
            };

            function pushRoute() {
                let route = getCustomer + '?page=' + $scope.query.page;
                history.pushState({urlPath: '/'}, "", route);
            }

            $scope.prevPageDisabled = function () {
                return $scope.query.page === 1 ? "disabled" : "";
            };

            $scope.pageCount = function () {
                return $scope.pagination.meta && $scope.pagination.meta.last_page;
            };

            $scope.nextPage = function () {
                if ($scope.query.page < $scope.pageCount()) {
                    $scope.query.page++;
                    $scope.paginationChanged()
                }
            };

            $scope.paginationChanged = function () {
                $scope.getCustomers();
                pushRoute();
            };

            $scope.nextPageDisabled = function () {
                return $scope.query.page === $scope.pageCount() ? "disabled" : "";
            };
            $scope.getCustomers = function () {
                $scope.loading = true;
                let array = [];
                let customers = $scope.getDataFromStore('customers');
                if (customers && JSON.parse(customers) && _.toArray(JSON.parse(customers)).length && $scope.pagination.meta && $scope.pagination.meta.last_page) {
                    if (JSON.parse(customers).hasOwnProperty($scope.query.page)) {
                        $scope.pagination.data = JSON.parse(customers)[$scope.query.page];
                        $scope.loading = false;
                        return true;
                    }
                    array = JSON.parse(customers);
                }
                $http.get(getCustomer + '?' + $.param($scope.query)).then(function (response) {
                    $scope.pagination = response.data;
                    array[$scope.query.page] = $scope.pagination.data;
                    $scope.setStore(array);
                    $scope.loading = false;
                });
            };
            pushRoute();
            $scope.getCustomers();

            $scope.updateStore = function () {
                let array = [];
                let customers = $scope.getDataFromStore('customers');
                if (customers && JSON.parse(customers)) {
                    array = JSON.parse(customers);
                    array[$scope.query.page] = $scope.pagination.data;
                    $scope.setStore(array);
                }
            };

            $scope.routeDD = function (route, location, customer) {
                route.dropdown('setting', {
                    forceSelection: false,
                    saveRemoteData: false,
                    onChange: function (value) {
                        locationDropDown(value, location, customer);
                        customer.changedRouteId = value;
                        $scope.updateStore();
                    }
                });
            };

            function locationDropDown(value, routeLocation, customer) {
                routeLocation.dropdown('clear');
                var routeId = value;
                var url = '{{ route('setting.route.location.search', ['routeId']) }}';
                url = url.replace('routeId', routeId);
                routeLocation.dropdown('setting', {
                    apiSettings: {
                        url: url + '/{query}',
                        cache: false,
                    },
                    saveRemoteData: false,
                    forceSelection: false,
                    onChange: function (value) {
                        customer.changedRouteLocationId = value;
                        $scope.updateStore();
                    }
                });
            }

            $scope.updateRoute = '{{ route('sales.customer.update.route') }}';
            $scope.updateRoutes = function () {
                $scope.loading = true;
                $http.post($scope.updateRoute, {customers: $scope.pagination.data}).then(function (response) {
                    $scope.loading = false;
                    swal("Updated!", "Customer route & location updated successfully.", "success");
                }).catch(function (response) {
                    $scope.errors = response.data.errors;
                    $scope.loading = false;
                });
            };

            $scope.hasErrors = function (name) {
                return $scope.errors.hasOwnProperty(name);
            };

            $scope.setDropdown = function (dd, text, value) {
                dd.dropdown('set text', text).dropdown('set value', value);
            }
        }]).directive('customerDirective', function () {
            return function (scope, element, attrs) {
                scope.routeDD(element.find('.route-drop-down'), element.find('.location-drop-down'), scope.customer);
                scope.setDropdown(element.find('.route-drop-down'), scope.customer.route.name, scope.customer.route.id);
                scope.setDropdown(element.find('.location-drop-down'), scope.customer.location.name + ' (' + scope.customer.location.code + ')', scope.customer.location.id);
            };
        });
    </script>
@endsection
