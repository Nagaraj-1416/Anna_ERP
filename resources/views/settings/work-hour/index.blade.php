@extends('layouts.master')
@section('title', 'Work Hours')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
    <div class="row" ng-controller="WorkHourController">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        <ul class="list-style-none">
                            <li class="text-muted m-t-20">FILTER HOURS BY</li>
                            <li class="divider"></li>
                            <li ng-class="{'active': !query.filter}"
                                ng-click="filterUpdate()"><a
                                        href="">All Hours</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyCreated'}"
                                ng-click="filterUpdate('recentlyCreated')"><a href="">Recently Created</a></li>
                            <li ng-class="{'active': query.filter === 'recentlyUpdated'}"
                                ng-click="filterUpdate('recentlyUpdated')"><a href="">Recently Modified</a></li>
                        </ul>
                        <hr>
                        <ul class="list-style-none">
                            <li class="text-muted" ng-click="resetFilters()">
                                <a class="text-primary" href="">Reset Filters</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.left-aside-column-->
                    <div class="right-aside custom-right-aside">

                        {{ form()->open([ 'route' => 'setting.work.hour.store', 'method' => 'POST']) }}
                        @include('settings.work-hour._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Allocate') !!}
                        {{ form()->close() }}

                        <hr class="m-t-20">

                        <div class="right-page-header m-t-20">
                            <div class="d-flex m-b-10">
                                <div class="align-self-center">
                                    <h2 class="card-title m-t-10">Work Hours @{{ total ? ("(" + total +")") :
                                        '' }}</h2>
                                </div>
                                <div class="ml-auto"></div>
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
                                <p>loading work hours</p>
                            </div>
                        </div>
                        <div class="row" ng-hide="loading">
                            <div class="col-md-12 m-b-20">
                                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list" data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>Staff details</th>
                                        <th>Date & allocation hours</th>
                                        <th>Other details</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <tr ng-repeat="hour in hours">
                                        <td style="vertical-align: middle;">
                                            <a target="_blank" href="#">
                                                @{{ hour.staff.full_name }}
                                            </a><br />
                                            <small>
                                                <i class="mdi mdi-email"></i> @{{ hour.staff.email }} |
                                                @{{  hour.company.name }}
                                            </small>
                                        </td>
                                        <td>
                                            @{{ hour.date | date }}<br />
                                            <b>Starts at:</b> @{{  hour.start }} | <b>Ends at:</b> @{{  hour.end }}
                                        </td>
                                        <td>
                                            <b>Allocated by:</b> @{{ hour.allocated_by.name }}
                                            <span ng-if="hour.terminated_by">
                                                <br />
                                                <b>Terminated by:</b> @{{ hour.terminated_by.name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="label label-success" ng-if="hour.status == 'Allocated'">Allocated</span>
                                            <span class="text-green" ng-if="hour.status == 'Allocated'"><br />Session is running now</span>
                                            <span class="label label-danger" ng-if="hour.status == 'Terminated'">Terminated</span>
                                            <span class="text-danger" ng-if="hour.status == 'Terminated'"><br />Session was terminated</span>
                                        </td>
                                        <td class="text-center">
                                            <a ng-if="hour.status == 'Allocated'" class="btn btn-danger btn-sm status-change" data-value="Terminated" href=""
                                               data-id="@{{ hour.id }}">Terminate
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
                        <div class="row" ng-hide="loading" ng-if="hours.length === 0 && !filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">You haven't added any work hours unit yet, fill above form and press allocate button to add</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- if there no credit available message -->
                        <div class="row" ng-hide="loading" ng-if="hours.length === 0 && filterd">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="card-text">There are <code>no</code> work hours found.</p>
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
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        var dropDown = $('.drop-down');
        dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        $('.status-change').click(function (e) {
            var $id = $(this).data('id');
            var updateUrl = '{{ route('setting.work.hour.change.status', ['workHour' => 'ID']) }}';
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ba1425',
                confirmButtonText: 'Yes, Terminate'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: updateUrl.replace('ID', $id),
                        type: 'PATCH',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Status Changed!',
                                'Allocated work hours terminated successfully!',
                                'success'
                            ).then(function (confirm) {
                                if (confirm) {
                                    window.location.reload()
                                }
                            });
                        }
                    });
                }
            });
        })
    </script>
    <script>
        app.controller('WorkHourController', ['$scope', '$http', function ($scope, $http) {
            var moduleRoute = '{{ route('setting.work.hour.index') }}';

            $scope.today = '{{ carbon()->toDateString() }}';

            $scope.hours = [];
            $scope.filterd = false;
            $scope.loading = true;
            $scope.pagination = {};
            $scope.currentPaginationPage = 0;
            $scope.query = {
                ajax: true,
                page: null,
                filter: null,
                search: null
            };

            $scope.filterUpdated = function () {
                $scope.filterd = true;
                $scope.query.search = $scope.searchHours;
                $scope.fetchHours();
            };

            $scope.filterUpdate = function (filter) {
                $scope.query.filter = filter ? filter : '';
                $scope.filterd = true;
                $scope.fetchHours();
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
                $scope.fetchHours();
            };

            $scope.fetchHours = function () {
                $scope.loading = true;
                $scope.query.page = $scope.currentPaginationPage + 1;
                var queryRoute = $.param($scope.query);
                $http.get(moduleRoute + '?' + queryRoute).then(function (response) {
                    $scope.loading = false;
                    $scope.hours = response.data.data;
                    $scope.pagination = response.data;
                    $scope.total = response.data.total;
                    $scope.range();
                });
            };
            $scope.fetchHours();

            $scope.resetFilters = function () {
                $scope.query = {
                    ajax: true,
                    page: 0,
                    filter: '',
                    search: ''
                };
                $scope.searchHours = '';
                $scope.filterd = false;
                $scope.fetchHours();
            };

            $scope.checkPagination = function () {
                return $scope.total > $scope.pagination.per_page;
            }
        }]);
    </script>
@endsection