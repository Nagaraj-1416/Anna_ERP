@extends('layouts.master')
@section('title', 'Staffs')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row" ng-controller="StaffController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        <a target="_blank" href="{{ route('setting.staff.create') }}" class="btn btn-info btn-block">
                            <i class="fa fa-plus"></i> Add New Staff
                        </a>
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER ROUTE BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !query.filter}"
                                ng-click="filterUpdate()"><a
                                        href="">All Staffs</a></li>
                            <li ng-class="{'active': query.filter === 'Active'}"
                                ng-click="filterUpdate('Active')"><a href="">Active Staffs</a></li>
                            <li ng-class="{'active': query.filter === 'Inactive'}"
                                ng-click="filterUpdate('Inactive')"><a href="">Inactive Staffs</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                                ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                                ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
                        </ul>
                        <ul class="list-style-none">
                            <li class="divider"></li>
                            <li class="m-t-10">Designation</li>
                            <li>
                                <div class="ui fluid  search selection dropdown designation-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a designation</div>
                                    <div class="menu"></div>
                                </div>
                            </li>

                            <li class="divider"></li>
                            <li class="m-t-10">Company</li>
                            <li>
                                <div class="ui fluid  search selection dropdown company-drop-down">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a company</div>
                                    <div class="menu"></div>
                                </div>
                            </li>
                        </ul>
                        <hr>
                        <ul class="list-style-none">
                            <li class="text-muted" ng-click="resetFilters()">
                                <a class="text-primary" href="">Reset Filters</a>
                            </li>
                        </ul>
                        <hr>
                        <p><b>Export to</b></p>
                        <a href="{{ route('setting.staff.export') }}" class="btn btn-pdf">
                            PDF
                        </a>
                        <a href="{{ route('setting.staff.export', ['type' => 'excel']) }}"
                           class="btn btn-excel">
                            Excel
                        </a>
                    </div>
                    <!-- /.left-aside-column-->
                    <div class="right-aside custom-right-aside">
                        <div class="right-page-header">
                            <div class="d-flex m-b-10">
                                <div class="align-self-center">
                                    <h2 class="card-title m-t-10">Staffs @{{ total ? ("(" + total +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto">
                                    <input type="text" id="demo-input-search2" ng-model="searchStaffs"
                                           placeholder="search for staff here" class="form-control"
                                           ng-change="filterUpdated()">
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
                                <p>loading staffs</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list"
                                       data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th colspan="2">Staff details</th>
                                        <th>Short name</th>
                                        <th>Role</th>
                                        <th>Joined date</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="staff in staffs">
                                        <td style="width: 3%;">
                                            <img src="@{{ getStaffImage(staff) }}" alt="user" class="img-circle"/>
                                        </td>
                                        <td>
                                            <a target="_blank" href="/setting/staff/@{{ staff.id }}">
                                                @{{ staff.full_name }}
                                            </a><br/>
                                            <small>
                                                <i class="mdi mdi-email"></i> @{{ staff.email }}
                                                <i class="mdi mdi-phone-classic"></i> @{{ staff.phone }}
                                                <i class="mdi mdi-cellphone"></i> @{{ staff.mobile }}
                                            </small>
                                        </td>
                                        <td>@{{ staff.short_name }}</td>
                                        <td>@{{ staff.user.role ? staff.user.role.name : 'None' }}</td>
                                        <td>@{{ staff.joined_date | date }}</td>
                                        <td>
                                            <span class="label label-success"
                                                  ng-if="staff.is_active == 'Yes'">Active</span>
                                            <span class="label label-danger"
                                                  ng-if="staff.is_active == 'No'">Inactive</span>
                                        </td>
                                        <td class="text-center">
                                            <a title="Update Staff Details" class="p-10"
                                               href="/setting/staff/@{{ staff.id }}/edit">
                                                <i class="ti-pencil" aria-hidden="true"></i>
                                            </a>
                                            <a href="" class="p-10 upload-staff-image"
                                               title="Upload Staff Profile Image" ng-click="uploadImageTrigger(staff)">
                                                <i class="ti-image" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- pagination panel -->
                            <div class="col-md-12" ng-show="checkPagination()">
                                @include('general.pagination.pagination')
                            </div>
                        </div>
                        <div class="row" ng-hide="loading" ng-if="staffs.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any staff yet, click on "Add New Staff"
                                            button to add staff.</p>
                                        <a target="_blank" href="{{ route('setting.staff.create') }}"
                                           class="btn btn-info">
                                            <i class="fa fa-plus"></i> Add New Staff
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no staff available message -->
                        <div class="row" ng-hide="loading" ng-if="staffs.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> staffs found.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- .left-aside-column-->
                    </div>
                    <!-- /.left-right-aside-column-->
                </div>
            </div>
        </div>
        @include('_inc.image.add', ['btn' => 'upload-image'])
    </div>
@endsection
@section('script')
    @include('_inc.image._inc.script', ['url' => route('setting.staff.upload', ['staff' => 'ID'])])
    <script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
    <script>
        app.controller('StaffController', ['$scope', '$http', function ($scope, $http) {
            var moduleStaff = '{{ route('setting.staff.index') }}';
            $scope.staffs = [];
            $scope.filterd = false;
            $scope.loading = true;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.query = {
                ajax: true,
                page: null,
                filter: null,
                search: null,
                designation: null,
                company: null,
            };
            $scope.designationDD = $('.designation-drop-down');
            $scope.companyDD = $('.company-drop-down');

            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchStaffs;
                $scope.fetchStaffs();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchStaffs();
            };
            $scope.range = function () {
                var rangeSize = 10;
                $scope.pages = [];
                var start;
                var ret = [];
                if ($scope.pagination.total < 10) {
                    rangeSize = $scope.pagination.total
                }
                start = $scope.currentPaginationPage > 5 ? $scope.currentPaginationPage - 5 : 0;
                if (start > $scope.pageCount() - rangeSize) {
                    start = $scope.pageCount() - rangeSize + 1;
                }

                for (var i = start; i < start + rangeSize; i++) {
                    if (i < 0) continue;
                    if (i >= $scope.pagination.last_page) continue;
                    ret.push(i);
                    $scope.pages.push(i);
                }
                return ret;
            };

            $scope.prevPage = function () {
                if ($scope.currentPaginationPage > 0) {
                    $scope.currentPaginationPage--;
                }
                $scope.paginationChanged()
            };

            $scope.prevPageDisabled = function () {
                return $scope.currentPaginationPage === 0 ? "disabled" : "";
            };

            $scope.pageCount = function () {
                return $scope.pagination.last_page - 1;
            };

            $scope.nextPage = function () {
                if ($scope.currentPaginationPage < $scope.pageCount()) {
                    $scope.currentPaginationPage++;
                }
                $scope.paginationChanged()
            };

            $scope.nextPageDisabled = function () {
                return $scope.currentPaginationPage === $scope.pageCount() ? "disabled" : "";
            };

            $scope.setPage = function (n) {
                if ($scope.pagination.current_page === n + 1) return;
                $scope.currentPaginationPage = n;
                $scope.paginationChanged()
            };
            $scope.paginationChanged = function () {
                $scope.fetchStaffs();
            };

            $scope.fetchStaffs = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryStaff = $.param($scope.query);
                $http.get(moduleStaff + '?' + queryStaff).then(function (response) {
                    $scope.loading = false;
                    $scope.staffs = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchStaffs();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: '',
                    company: null,
                    designation: null,
                };
                $scope.searchStaffs = '';
                $scope.filterd = false;
                $scope.fetchStaffs();
                $scope.designationDD.dropdown('clear');
                $scope.companyDD.dropdown('clear');
            };

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            };

            $scope.getStaffImage = function (staff) {
                var route = '{{ route('setting.staff.image', ['staff' => 'STAFF']) }}';
                return route.replace('STAFF', staff.id)
            };

            $scope.designationDD.dropdown({
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: '{{ route('setting.designation.search') }}' + '/{query}',
                    cache: false
                },
                onChange: function (value) {
                    $scope.query.designation = value;
                    $scope.filterd = true;
                    $scope.fetchStaffs();
                }
            });


            $scope.companyDD.dropdown({
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: '{{ route('setting.company.search') }}' + '/{query}',
                    cache: false
                },
                onChange: function (value) {
                    $scope.query.company = value;
                    $scope.filterd = true;
                    $scope.fetchStaffs();
                }
            });

            $scope.el = {
                'loader': $('.image-preloader'),
            };


            $scope.imageSlider = $('#image-sidebar').slideReveal({
                position: "right",
                width: '400px',
                push: false,
                overlay: true,
                shown: function (slider, trigger) {
                    // init scroll for side bar body
                    $('#image-body').slimScroll({
                        color: 'gray',
                        height: '100%',
                        railVisible: true,
                        alwaysVisible: false
                    });
                },
                show: function (slider, trigger) {
                    $scope.hideLoader();
                    $scope.resetForm();
                },
            });
            $scope.selectedProduct = {};
            initSide($scope, $http);
            // Image Upload

            $scope.uploadImageTrigger = function (staff) {
                $scope.selectedProduct = staff;
                $scope.imageSlider.slideReveal("show");
            };
        }]);
    </script>
@endsection