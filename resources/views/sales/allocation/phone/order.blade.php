@extends('layouts.master')
@section('title', 'Create Allocation')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Attach Phone Orders') !!}
@endsection
@section('content')
    <div class="row" ng-controller="AllocationController">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">
                        Attach orders
                    </h3>
                </div>
                <div class="card-body">
                    {!! form()->model($allocation, ['url' => route('sales.allocation.attach.phone.order', $allocation), 'method' => 'POST']) !!}
                    <div class="form-body">
                        <div>
                            <h4 class="box-title">Phone Orders</h4>
                            <h6 style="padding-top: 2px;">
                                <small>Pick orders to associate</small>
                                <br>
                            </h6>
                            <hr>
                            <div class="m-b-10">
                                <input type="text" style="margin-left: 0 !important;" id="demo-input-search2"
                                       ng-model="orderSearch"
                                       placeholder="search for orders here" class="form-control" autocomplete="off">
                            </div>
                            <div id="order-section">
                                <table class="ui table bordered celled">
                                    <thead>
                                    <tr>
                                        <th style="width: 2%;">
                                            <input type="checkbox" id="order_select_all"
                                                   name="order_select_all"
                                                   class="chk-col-cyan order-check"
                                                   ng-click="handleCustomerCheckAll($event)">
                                            <label for="order_select_all"></label>
                                        </th>
                                        <th>Order No</th>
                                        <th>Order Date</th>
                                        <th>Customer Name</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-right">Paid</th>
                                        <th class="text-right">Balance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="@{{ oldCustomers.hasOwnProperty(order.id) ? 'td-bg-danger' : '' }}"
                                        ng-show="orders.length"
                                        ng-repeat="(key, order) in orders | filter:orderSearch" order-directive>
                                        <td style="width: 2%;">
                                            <div class="demo-checkbox">
                                                <input type="checkbox" id="@{{ 'md_checkbox_28_' + order.id }}"
                                                       name="orders[@{{ order.id }}]"
                                                       class="chk-col-cyan order-check"
                                                       data-order="@{{ order.id }}">
                                                <label for="@{{ 'md_checkbox_28_' + order.id }}"></label>
                                            </div>
                                        </td>
                                        <td>@{{ order.order_no }}</td>
                                        <td>@{{ order.order_date }}</td>
                                        <td>@{{ order.customer.display_name }}</td>
                                        <td class="text-right">@{{ order.total | number:2 }}</td>
                                        <td class="text-right">@{{ order.paid | number:2 }}</td>
                                        <td class="text-right">@{{ order.balance | number:2 }}</td>
                                    </tr>
                                    <tr ng-show="!orders.length">
                                        <td colspan="7">
                                            No orders to display...
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! form()->bsSubmit('Submit', 'btn btn-success waves-effect waves-light m-r-10', 'Save', 'submit') !!}
                    {!! form()->bsCancel('Cancel', 'sales.allocation.show', [$allocation]) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
    <style>
        .error {
            color: red;
        }
    </style>
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    <script>
        app.controller('AllocationController', ['$scope', '$http', function ($scope, $http) {
            $scope.orders = @json($orders);

            $scope.handleCustomerCheckAll = function ($event) {
                if ($($event.target).is(':checked')) {
                    $('.order-check').prop('checked', true);
                } else {
                    $('.order-check').prop('checked', false);
                }
            }
        }]).directive('orderDirective', function () {
            return function (scope, element, attr) {

            };
        });

    </script>
@endsection