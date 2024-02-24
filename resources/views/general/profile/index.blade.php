@extends('layouts.master')
@section('title', 'Profile')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Profile') !!}
@endsection
@section('content')
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <!-- Column -->
            <div class="card">
                <img class="card-img-top" src="{{ asset('images/background/profile-bg.jpg') }}" alt="Card image cap">
                <div class="card-body little-profile">
                    <div class="text-center">
                        <div class="pro-img"><img src="{{route('setting.staff.image', [$staff])}}" alt="user"></div>
                        <h3 class="m-b-0">{{ $staff->full_name or 'Staff name' }}</h3>
                    </div>
                    <hr>
                    <span class="text-muted"><b>Address</b></span>
                    <br />
                    <br />
                    <span>
                        @if($address)
                            {{ $address->street_one }}
                            @if($address->street_two)
                                {{ $address->street_two }},
                            @endif
                            @if($address->city)
                                {{ $address->city }},
                            @endif
                            @if($address->province)
                                {{ $address->province }},
                            @endif
                            @if($address->postal_code)
                                {{ $address->postal_code }},
                            @endif
                            @if($address->country)
                                {{ $address->country->name }}.
                            @endif
                        @endif
                    </span>
                </div>
                <div class="card-body">
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show" data-toggle="tab" href="#profile" role="tab"
                           aria-selected="false">
                            <i class="ti-user"></i> PROFILE INFO
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#updateProfile" role="tab" aria-selected="false">
                            <i class="ti-pencil-alt"></i> UPDATE PROFILE
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#activities" role="tab" aria-selected="true">
                            <i class="ti-time"></i> ACTIVITIES
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- PROFILE Tab -->
                    <div class="tab-pane active show" id="profile" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-xs-6 b-r"><strong>Short name</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->short_name or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r"><strong>Is staff active</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->is_active or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r"><strong>First name</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->first_name or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6"><strong>Last name</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->last_name or 'None' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-xs-6 b-r"><strong>Gender</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->gender or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r"><strong>Date of birth</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->dob or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r"><strong>Joined date</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->joined_date or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6"><strong>Resigned date</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->resigned_date or 'None' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-xs-6 b-r"><strong>Phone</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->phone or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r"><strong>Mobile</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->mobile or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r"><strong>Email</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->email or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6"><strong>Designation</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->designation->name or 'None' }}</p>
                                </div>
                            </div>

                            <h5 class="box-title box-title-with-margin">Finance Details</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-3 col-xs-6 b-r"><strong>Bank name</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->bank_name or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r"><strong>Branch</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->branch or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r"><strong>Account name</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->account_name or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6"><strong>Account no</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->account_no or 'None' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-xs-12 b-r"><strong>EPF No</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->epf_no or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-12 b-r"><strong>ETF No</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->etf_no or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-12"><strong>Pay rate</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->pay_rate or 'None' }}</p>
                                </div>
                            </div>

                            <h5 class="box-title box-title-with-margin">Login Details</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-3 col-xs-12 b-r"><strong>Email</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->user->email or 'None' }}</p>
                                </div>
                                <div class="col-md-3 col-xs-12"><strong>Role</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->user->role->name or 'None' }}</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- UPDATE PROFILE tab -->
                    <div class="tab-pane" id="updateProfile" role="tabpanel">
                        <div class="card-body">
                            @include('general.profile._inc.update-profile')
                        </div>
                    </div>

                    <!-- ACTIVITIES Tab -->
                    <div class="tab-pane" id="activities" role="tabpanel" ng-controller="ActivityController">
                        <div class="card-body">
                            <div class="profiletimeline">
                                <div class="sl-item" dir-paginate="activity in activities | itemsPerPage:10">
                                    <div class="sl-left">
                                        <img src="@{{  activity.profile }}" alt="user"
                                             class="img-circle">
                                    </div>
                                    <div class="sl-right">
                                        <div>
                                            <a href="#" class="link">@{{ activity.causer.name }}</a>
                                            <span class="sl-date">@{{ activity.diffForHumans}}</span>
                                            <div class="m-t-5 row">
                                                <div class="col-md-12 col-xs-12">
                                                    <p style="text-transform: capitalize;"> @{{ activity.log_name + ' '
                                                        + activity.description }} </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="pull-right">
                                <dir-pagination-controls></dir-pagination-controls>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        app.controller('ActivityController', ['$scope', '$http', function ($scope, $http) {
            $scope.activities = @json(activitiesForProfile(auth()->user(), $staff)->toArray());
        }]);
    </script>
@endsection