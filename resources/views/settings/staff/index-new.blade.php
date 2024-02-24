@extends('layouts.master')
@section('title', 'Staff')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Staff') !!}
@endsection
@section('content')
    <div class="row" ng-controller="IndexController">
        <div class="col-12">
            <div class="staff card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside left-aside-medium-width" id="leftSide">

                        <a target="_blank" href="{{ route('setting.staff.create') }}"
                           class="btn btn-info m-t-10 m-b-20 btn-block waves-effect waves-light">
                            <i class="fa fa-plus"></i> Add New Staff
                        </a>

                        <div class="form-material">
                            <input class="form-control p-20" ng-model="searchText" type="text"
                                   placeholder="Search Staff">
                        </div>

                        <div class="message-box contact-box" id="scrollDiv">
                            <div class="message-widget contact-widget" >
                                <a class="message-widget-item-custom "
                                   ng-class="staff.id === selectedItem.id ? 'active' : ''" ng-click="selectItem(staff)"
                                   ng-repeat="staff in datas | filter:searchText as results" ng-show="getCount(datas)">
                                    <div class="mail-contnet mail-content-custom">
                                        <h6>@{{ staff.salutation }} @{{ staff.full_name }}</h6>
                                        <span class="text-muted mail-desc">@{{ staff.code }}</span>
                                    </div>
                                </a>
                                <div class="text-center" ng-if="results.length === 0"><p>No data Available</p></div>
                            </div>

                        </div>

                    </div>
                    <!-- /.left-aside-column-->
                    <div class="right-aside right-aside-medium-width">
                        <div class="loading" ng-show="loading">
                            <div class="loading-dot"></div>
                            <div class="loading-dot"></div>
                            <div class="loading-dot"></div>
                            <div class="loading-dot"></div>
                        </div>
                        <div ng-show="selectedItem" id="form">
                            <div class="right-page-header">
                                <div class="d-flex">
                                    <div class="align-self-center">
                                        <h2 class="card-title m-t-10">Staff Details </h2>
                                    </div>
                                    <div class="ml-auto"></div>
                                </div>
                            </div>
                            <!-- action buttons -->
                            <div>
                                <div class="row m-b-10">
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <a href="@{{ selectedItem.id }}/edit"
                                               class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                                <i class="fa fa-pencil"></i> Edit
                                            </a>
                                        </div>
                                        <div class="pull-right"></div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class=""><img src="@{{ getImage(selectedItem) }}" alt="user"
                                                               class="img-circle" width="100"></div>
                                            <div class="p-l-20">
                                                <h3 class="font-medium">
                                                    @{{ selectedItem.salutation ? selectedItem.salutation : 'None' }}
                                                    @{{ selectedItem.full_name ? selectedItem.full_name : 'None' }}
                                                    <i class="fa @{{ selectedItem && selectedItem.gender == 'Male' ? 'fa-male' : 'fa-female' }}"></i>
                                                </h3>
                                                <h6 class="text-muted m-t-5">@{{ selectedItem.designation ?
                                                    selectedItem.designation : 'None' }}</h6>
                                                <label class="label label-inverse m-t-5">
                                                    <i class="fa fa-envelope-o"></i> @{{ selectedItem.email ?
                                                    selectedItem.email : 'None' }}
                                                </label>
                                                <label class="label label-inverse m-t-5">
                                                    <i class="fa fa-phone"></i> @{{ selectedItem.mobile ?
                                                    selectedItem.mobile : 'None' }}
                                                </label>
                                                <label class="label label-inverse m-t-5">
                                                    <i class="fa fa-phone"></i> @{{ selectedItem.phone ?
                                                    selectedItem.phone : 'None' }}
                                                </label>
                                                <label class="label label-inverse m-t-5">
                                                    <i class="fa fa-gift"></i> @{{ selectedItem.dob ? selectedItem.dob
                                                    : 'None' }}
                                                </label>
                                                <p class="text-muted">Address will be pasted here...</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-12 b-r"><strong>Joined date</strong>
                                                <br>
                                                <p class="text-muted">@{{ selectedItem ? selectedItem.joined_date :
                                                    'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-12"><strong>Resigned date</strong>
                                                <br>
                                                <p class="text-muted">@{{ selectedItem.resigned_date ?
                                                    selectedItem.resigned_date : 'None' }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12"><strong>Notes</strong>
                                                <br>
                                                <p class="text-muted">@{{ selectedItem.notes ? selectedItem.notes :
                                                    'None' }}</p>
                                            </div>
                                        </div>

                                        <h5 class="box-title box-title-with-margin">Finance Details</h5>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-12 b-r"><strong>Bank name</strong>
                                                <br>
                                                <p class="text-muted">@{{ selectedItem.bank_name ?
                                                    selectedItem.bank_name : 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-12 b-r"><strong>Branch</strong>
                                                <br>
                                                <p class="text-muted">@{{ selectedItem.branch ? selectedItem.branch :
                                                    'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-12 b-r"><strong>Account name</strong>
                                                <br>
                                                <p class="text-muted">@{{ selectedItem.account_name ?
                                                    selectedItem.account_name : 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-12"><strong>Account no</strong>
                                                <br>
                                                <p class="text-muted">@{{ selectedItem.account_no ?
                                                    selectedItem.account_no : 'None' }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-12 b-r"><strong>EPF No</strong>
                                                <br>
                                                <p class="text-muted">@{{ selectedItem.epf_no ? selectedItem.epf_no :
                                                    'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-12 b-r"><strong>ETF No</strong>
                                                <br>
                                                <p class="text-muted">@{{ selectedItem.etf_no ? selectedItem.etf_no :
                                                    'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-12"><strong>Pay rate</strong>
                                                <br>
                                                <p class="text-muted">@{{ selectedItem.pay_rate ?
                                                    selectedItem.pay_rate : 'None' }}</p>
                                            </div>
                                        </div>

                                        <h5 class="box-title box-title-with-margin">Login Details</h5>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-12 b-r"><strong>Email</strong>
                                                <br>
                                                <p class="text-muted">@{{ selectedItem.user ? selectedItem.user.email
                                                    : 'None' }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-12 b-r"><strong>Role</strong>
                                                <br>
                                                <p class="text-muted">@{{ selectedItem.user && selectedItem.user.role
                                                    ? selectedItem.user.role.name : 'None' }}</p>
                                            </div>
                                            <div class="col-md-6 col-xs-12"><strong>Associated Business Types</strong>
                                                <br>
                                                <p class="text-muted">{{ 'Need to get all associated business types here' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- recent comments -->
                                    <div class="col-md-6">
                                        @include('general.comment-new.index')
                                    </div>
                                    <!-- recent audit logs -->
                                    <div class="col-md-6">
                                        @include('general.log-new.index')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.left-right-aside-column-->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('general.index.script', ['routes' => [
    'index' => route('setting.staff.new.index'),
    'image' => route('setting.staff.image', ['staff' => 'MODEL']),
    'show' => route('setting.staff.show', ['staff' => 'MODEL']),
    'logs' => route('log.get', ['model' => 'App\\Staff', 'modelId' => 'ID']),
    'comments' => route('comment.get', ['model' => 'App\\Staff', 'modelId' => 'ID']),
    ], 'modelName' => 'App\\\Staff'])
@endsection