@extends('layouts.master')
@section('title', 'Settings')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div ng-controller="SettingController">
        <div class="row">
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Quick Links</h4>
                        <div class="list-group">
                            <a target="_blank" href="{{ route('setting.product.create') }}" class="list-group-item"><i
                                        class="fa fa-plus"></i> New Product</a>
                            <a target="_blank" href="{{ route('setting.vehicle.create') }}" class="list-group-item"><i
                                        class="fa fa-plus"></i> New Vehicle</a>
                            <a target="_blank" href="#" class="list-group-item"><i class="fa fa-plus"></i> New
                                Machine</a>
                            <a target="_blank" href="{{ route('setting.staff.create') }}" class="list-group-item"><i
                                        class="fa fa-plus"></i> New Staff</a>
                            <a target="_blank" href="{{ route('setting.rep.create') }}" class="list-group-item"><i
                                        class="fa fa-plus"></i> New Sales Rep</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->

            <!-- Column -->
            <div class="col-lg-9 col-md-6">
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-primary">
                                    <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <h3 class="m-b-0 text-primary">@{{ productsCount }}</h3>
                                    <h5 class="text-muted m-b-0">Total Products</h5></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-info">
                                    <h3 class="text-white box m-b-0"><i class="fa fa-car"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <h3 class="m-b-0 text-info">@{{ vehiclesCount }}</h3>
                                    <h5 class="text-muted m-b-0">Total Vehicles</h5></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-warning">
                                    <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <h3 class="m-b-0 text-warning">0</h3>
                                    <h5 class="text-muted m-b-0">Total Machines</h5></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-success">
                                    <h3 class="text-white box m-b-0"><i class="mdi mdi-routes"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <h3 class="m-b-0 text-success">@{{ routeCount }}</h3>
                                    <h5 class="text-muted m-b-0">Total Routes</h5></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-danger">
                                    <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <h3 class="m-b-0 text-danger">@{{ repsCount }}</h3>
                                    <h5 class="text-muted m-b-0">Total Sales Reps</h5></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-primary">
                                    <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <h3 class="m-b-0 text-primary">@{{ salesVanCount }}</h3>
                                    <h5 class="text-muted m-b-0">Total Sales Vans</h5></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-info">
                                    <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <h3 class="m-b-0 text-info">@{{ shopCount }}</h3>
                                    <h5 class="text-muted m-b-0">Total Shops</h5></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-success">
                                    <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <h3 class="m-b-0 text-success">@{{ staffsCount }}</h3>
                                    <h5 class="text-muted m-b-0">Total Staff</h5></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-inverse">
                                    <h3 class="text-white box m-b-0"><i class="fa fa-user-o"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <h3 class="m-b-0 text-inverse">@{{ usersCount }}</h3>
                                    <h5 class="text-muted m-b-0">Total Users</h5></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body bg-info">
                        <h4 class="text-white card-title">Staff</h4>
                        <h6 class="card-subtitle text-white m-b-0 op-5">Recently added staff</h6>
                    </div>
                    <div class="card-body">
                        <div class="message-box contact-box">
                            <h2 class="add-ct-btn">
                                <button type="button" class="btn btn-circle btn-lg btn-success waves-effect waves-dark">
                                    +
                                </button>
                            </h2>
                            <div class="message-widget contact-widget">
                                <!-- Message -->
                                <a href="#" ng-repeat="staff in staffs">
                                    <div class="user-img"><img src="@{{ staffImage(staff) }}" alt="user"
                                                               class="img-circle">
                                        {{--<span class="profile-status online pull-right"></span>--}}
                                    </div>
                                    <div class="mail-contnet">
                                        <h5>@{{ staff.full_name }}</h5> <span
                                                class="mail-desc">@{{ staff.email }}</span></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body bg-inverse">
                        <h4 class="text-white card-title">Users</h4>
                        <h6 class="card-subtitle text-white m-b-0 op-5">Recently added users</h6>
                    </div>
                    <div class="card-body">
                        <div class="message-box contact-box">
                            <h2 class="add-ct-btn">
                                <button type="button" class="btn btn-circle btn-lg btn-success waves-effect waves-dark">
                                    +
                                </button>
                            </h2>
                            <div class="message-widget contact-widget">
                                <!-- Message -->
                                <a href="#" ng-repeat="user in users">
                                    <div class="user-img"><img src="{{ asset('images/users/1.jpg') }}" alt="user"
                                                               class="img-circle"> <span
                                                class="profile-status online pull-right"></span></div>
                                    <div class="mail-contnet">
                                        <h5>@{{user.name }}</h5> <span class="mail-desc">@{{ user.email }}</span></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body bg-primary">
                        <h4 class="text-white card-title">Sales Reps</h4>
                        <h6 class="card-subtitle text-white m-b-0 op-5">Recently added sales reps</h6>
                    </div>
                    <div class="card-body">
                        <div class="message-box contact-box">
                            <h2 class="add-ct-btn">
                                <button type="button" class="btn btn-circle btn-lg btn-success waves-effect waves-dark">
                                    +
                                </button>
                            </h2>
                            <div class="message-widget contact-widget">
                                <!-- Message -->
                                <a href="#" ng-repeat="rep in reps">
                                    <div class="user-img"><img src="{{ asset('images/users/1.jpg') }}" alt="user"
                                                               class="img-circle"> <span
                                                class="profile-status online pull-right"></span></div>
                                    <div class="mail-contnet">
                                        <h5>@{{ rep.name }}</h5> <span class="mail-desc">@{{ rep.email }}</span></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <h4 class="card-title">Recently Added Products</h4>
                            <div class="ml-auto">
                                <button class="pull-right btn btn-sm btn-rounded btn-success" data-toggle="modal"
                                        data-target="#myModal"><i class="fa fa-plus"></i> Add Product
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive m-t-20">
                            <table class="table stylish-table">
                                <thead>
                                <tr>
                                    <th colspan="2">Product</th>
                                    <th>Code</th>
                                    <th>Reorder Level</th>
                                    <th>In Stock</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="product in products">
                                    <td style="width:50px;"><img class="round" src="{{ asset('images/users/1.jpg') }}">
                                    </td>
                                    <td>
                                        <h6>@{{ product.name }}</h6>
                                        <small class="text-muted">@{{ product.type }}</small>
                                    </td>
                                    <td>@{{ product.code }}</td>
                                    <td>@{{ product.min_stock_level + ' '+ product.measurement}}</td>
                                    <td>@{{ product.stock_level + ' ' + product.measurement }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Recent Audit Logs</h4>
                        <ul class="feeds">
                            <li ng-repeat="activity in activites">
                                <div class=""><img class="round" src="{{ asset('images/users/1.jpg') }}"></div>
                                <a href="" style="text-transform: capitalize">@{{ activity.log_name + ' '+
                                    activity.description }}</a> <span class="text-muted">@{{ activity.created }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        app.controller('SettingController', ['$scope', '$http', function ($scope, $http) {
            //product data
            var productRoute = '{{ route('setting.summary.index', ['model' => 'Product', 'take' => '6', 'with' => 'stocks']) }}';
            $http.get(productRoute + '?ajax=true').then(function (response) {
                var data = response.data;
                $scope.products = data.model;
                $scope.productsCount = data.count;
            });

            //Vehicles
            var vehicleRoute = '{{ route('setting.summary.index', ['model' => 'Vehicle']) }}';
            $http.get(vehicleRoute + '?ajax=true').then(function (response) {
                var data = response.data;
                $scope.vehiclesCount = data.count;
            });

            //Routes
            var routeRoute = '{{ route('setting.summary.index', ['model' => 'Route']) }}';
            $http.get(routeRoute + '?ajax=true').then(function (response) {
                var data = response.data;
                $scope.routeCount = data.count;
            });

            //Reps
            var repRoute = '{{ route('setting.summary.index', ['model' => 'Rep', 'take' => '4']) }}';
            $http.get(repRoute + '?ajax=true').then(function (response) {
                var data = response.data;
                $scope.reps = data.model;
                $scope.repsCount = data.count;
            });

            //Staff data
            var staffRoute = '{{ route('setting.summary.index', ['model' => 'Staff', 'take' => '4']) }}';
            $scope.staffs = [];
            $http.get(staffRoute + '?ajax=true').then(function (response) {
                var data = response.data;
                $scope.staffs = data.model;
                $scope.staffsCount = data.count;
            });

            //Users data
            var userRoute = '{{ route('setting.summary.index', ['model' => 'User', 'take' => '4']) }}';
            $http.get(userRoute + '?ajax=true').then(function (response) {
                var data = response.data;
                $scope.users = data.model;
                $scope.usersCount = data.count;
            });

            // getStaff Image
            $scope.staffImage = function (staff) {
                var route = '{{ route('setting.staff.image', ['staff' => 'STAFF']) }}';
                return route.replace('STAFF', staff.id)
            };
            //Audit log data
            var auditLogs = '{{ route('setting.summary.index', ['model' => 'Activity', 'take' => '6', 'with' => 'user']) }}';
            $http.get(auditLogs + '?ajax=true').then(function (response) {
                var data = response.data;
                $scope.activites = data.model;
            });
            // Sales van count
            var salesVan = '{{ route('setting.summary.index', ['model' => 'Sales Van']) }}';
            $http.get(salesVan + '?ajax=true').then(function (response) {
                var data = response.data;
                $scope.salesVanCount = data.count;
            });
            // shop count
            var shopRoute = '{{ route('setting.summary.index', ['model' => 'Shop']) }}';
            $http.get(shopRoute + '?ajax=true').then(function (response) {
                var data = response.data;
                $scope.shopCount = data.count;
            });
        }]);
    </script>
@endsection
