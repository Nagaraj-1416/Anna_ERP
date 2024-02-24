@extends('layouts.master')
@section('title', 'Chart of Accounts')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Finance') !!}
@endsection
@section('content')
    <div class="row" ng-controller="AccountController">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <div class="pull-left">
                        <h3 class="card-title">Chart of Accounts</h3>
                        <h6 class="card-subtitle">Need to place some description about chart of accounts</h6>
                    </div>
                    <div class="pull-right">
                        <button onclick="createAccount()" class="btn btn-info">
                            <i class="fa fa-plus"></i> Create an Account
                        </button>
                    </div>
                    <div class="clearfix"></div>
                    {{--<div class="m-t-10">--}}
                        {{--<input type="text" style="margin-left: 0 !important;"--}}
                               {{--ng-model="accountSearch" placeholder="type your keywords here and search for accounts"--}}
                               {{--class="form-control"--}}
                               {{--autocomplete="off">--}}
                    {{--</div>--}}
                </div>
                <div class="row">
                    <div class="col-md-12" style="min-height: 200px">
                        <div class="account-preloader">
                            <svg class="circular" viewBox="25 25 50 50">
                                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                                        stroke-miterlimit="10"/>
                            </svg>
                        </div>

                        <div class="table-responsive">
                            <table class=" mt-3 table-nested table color-table muted-table borderless">
                                <thead>
                                <tr>
                                    <th>Account details</th>
                                    <th></th>
                                    <th class="text-right">Opening balance</th>
                                    <th>Opening balance as at</th>
                                    <th class="text-right">Debit</th>
                                    <th class="text-right">Credit</th>
                                    <th class="text-right">Account balance</th>
                                </tr>
                                </thead>
                                <tbody ng-if="groups.length == 0">
                                <tr>
                                    <td colspan="8" class="child-row-table">
                                        <p class="pl-3 no-data-info text-danger">
                                            No records found.
                                        </p>
                                    </td>
                                </tr>
                                </tbody>
                                <tbody ng-class="{opened: group.opened}"
                                       ng-include="&#39;/template/groupTableTree.tpl.html&#39;"
                                       ng-repeat="group in groups | filter:accountSearch" account-loop></tbody>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- include create account model -->
    @include('finance.account.create')
    @include('finance.account.opening.index')
@endsection

@section('style')

@endsection
@include('finance.account.opening.script')
@section('script')
    @include('finance.account._inc.script')
@endsection
