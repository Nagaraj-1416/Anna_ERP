@extends('layouts.master')
@section('title', $user->name)
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, $user->name) !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $user->name or 'None' }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Short name</strong>
                            <br>
                            <p class="text-muted">{{ $staff->short_name or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6"> <strong>Is staff active</strong>
                            <br>
                            <p class="text-muted">{{ $staff->is_active or 'None' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-xs-6 b-r"> <strong>First name</strong>
                            <br>
                            <p class="text-muted">{{ $staff->first_name or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Last name</strong>
                            <br>
                            <p class="text-muted">{{ $staff->last_name or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Gender</strong>
                            <br>
                            <p class="text-muted">{{ $staff->gender or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6"> <strong>Date of birth</strong>
                            <br>
                            <p class="text-muted">{{ $staff->dob or 'None' }}</p>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-3 col-xs-6 b-r"> <strong>Phone</strong>
                            <br>
                            <p class="text-muted">{{ $staff->phone or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Mobile</strong>
                            <br>
                            <p class="text-muted">{{ $staff->mobile or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Email</strong>
                            <br>
                            <p class="text-muted">{{ $staff->email or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6"> <strong>Designation</strong>
                            <br>
                            <p class="text-muted">{{ $staff->designation or 'None' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Joined date</strong>
                            <br>
                            <p class="text-muted">{{ $staff->joined_date or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6"> <strong>Resigned date</strong>
                            <br>
                            <p class="text-muted">{{ $staff->resigned_date or 'None' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-6"> <strong>Note</strong>
                            <br>
                            <p class="text-muted">{{ $staff->notes or 'None' }}</p>
                        </div>
                    </div>

                    <h5 class="box-title box-title-with-margin">Finance Details</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Bank name</strong>
                            <br>
                            <p class="text-muted">{{ $staff->bank_name or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Branch</strong>
                            <br>
                            <p class="text-muted">{{ $staff->branch or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Account name</strong>
                            <br>
                            <p class="text-muted">{{ $staff->account_name or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6"> <strong>Account no</strong>
                            <br>
                            <p class="text-muted">{{ $staff->account_no or 'None' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-xs-12 b-r"> <strong>EPF No</strong>
                            <br>
                            <p class="text-muted">{{ $staff->epf_no or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-12 b-r"> <strong>ETF No</strong>
                            <br>
                            <p class="text-muted">{{ $staff->etf_no or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-12"> <strong>Pay rate</strong>
                            <br>
                            <p class="text-muted">{{ $staff->pay_rate or 'None' }}</p>
                        </div>
                    </div>

                    <h5 class="box-title box-title-with-margin">Login Details</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3 col-xs-12 b-r"> <strong>Email</strong>
                            <br>
                            <p class="text-muted">{{ $staff->user->email or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-12"> <strong>Role</strong>
                            <br>
                            <p class="text-muted">{{ $staff->user->role->name or 'None' }}</p>
                        </div>
                    </div>

                    <h5 class="box-title box-title-with-margin">Address</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3 col-xs-12 b-r"> <strong>Street one</strong>
                            <br>
                            <p class="text-muted">{{ $address->street_one or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-12 b-r"> <strong>Street two</strong>
                            <br>
                            <p class="text-muted">{{ $address->street_two or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-12 b-r"> <strong>City</strong>
                            <br>
                            <p class="text-muted">{{ $address->city or 'None' }}</p>
                        </div>
                        <div class="col-md-3 col-xs-12"> <strong>Province</strong>
                            <br>
                            <p class="text-muted">{{ $address->province or 'None' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
