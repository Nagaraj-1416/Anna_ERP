@extends('layouts.master')
@section('title', 'Staff Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline-inverse">
            <div class="card-header">
                <h4 class="m-b-0 text-white">{{ $staff->code }}</h4>
            </div>
            <div class="card-body">
                <!-- action buttons -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                            @can('edit', $staff)
                                <a href="{{ route('setting.staff.edit', $staff->id) }}" class="btn waves-effect waves-light btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                            @endcan
                            @can('delete', $staff)
                                <button id="delete-btn" class="btn waves-effect waves-light btn-danger btn-sm" data-id="{{ $staff->id }}">
                                    <i class="fa fa-remove"></i> Delete
                                </button>
                            @endcan
                            @if($staff->user_id)
                                <a href="{{ route('setting.face.id.index', $staff->user->id) }}" class="btn waves-effect waves-light btn-warning btn-sm" target="_blank">
                                    <i class="fa fa-user"></i> Faces
                                </a>
                            @endif
                        </div>
                        <div class="pull-right"></div>
                    </div>
                </div>

                <div class="row custom-top-margin">
                    <div class="col-md-9">
                        <div class="card card-body">
                            <h3>
                                <b>{{ $staff->full_name }}</b>
                                <span class="pull-right text-muted">
                                    @if($staff->is_active == 'Yes')
                                        {{ 'Active' }}
                                    @else
                                        {{ 'Inactive' }}
                                    @endif
                                </span>
                            </h3>
                            <hr>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="card blog-widget">
                                        <div class="card-body">
                                            <div class="blog-image">
                                                <img src="{{route('setting.staff.image', [$staff])}}" alt="img" class="img-responsive">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
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
                                            <p class="text-muted">{{ $staff->designation->name or 'None' }}</p>
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
                                <div class="col-md-6 col-xs-12"> <strong>Allowed non-working hours?</strong>
                                    <br>
                                    <p class="text-muted">{{ $staff->user->allowed_non_working_hrs or 'None' }}</p>
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

                            @if($staff->notes)
                                <h5 class="box-title box-title-with-margin">Notes</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12 col-xs-6">
                                        <p class="text-muted">{{ $staff->notes or 'None' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3><b>ATTACHMENTS</b></h3>
                                <hr>
                                @include('_inc.document.view', ['model' => $staff])
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3">

                        <!-- associated companies -->
                        @if($staff->companies)
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Associated Companies</h4>
                                <hr>
                                @foreach($staff->companies as $company)
                                    <div>
                                        <p>
                                            <a href="{{ route('setting.company.show', $company) }}" target="_blank">
                                                {{ $company->name }}
                                            </a>
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- recent comments -->
                        <div class="card">
                            <div class="card-body">
                                @include('general.comment.index', ['model' => $staff])
                            </div>
                        </div>

                        <!-- recent audit logs -->
                        <div class="card">
                            <div class="card-body">
                                @include('general.log.index', ['model' => $staff, 'modelName' => 'Staff'])
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    @include('general.comment.script', ['modelId' => $staff->id])
    @include('_inc.document.script', ['model' => $staff])
    @parent
    @can('delete', $staff)
    <script>
        $('#delete-btn').click( function() {
            var id = $(this).data('id');
            var deleteUrl  = '{{ route('setting.staff.delete', [ 'staff'=>'ID']) }}';
            deleteUrl = deleteUrl.replace('ID', id);
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DB2828',
                confirmButtonText: 'Yes, Delete!'
            }).then(function (isConfirm) {
                if (isConfirm.value){
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {'_token' : '{{ csrf_token() }}'},
                        success: function(result) {
                            var type = result.success ? 'success' : 'warning';
                            var title = result.success ? 'Deleted!' : 'Unable to delete!';
                            swal(title, result.message, type);
                            if (type === 'success'){
                                setTimeout(function () {
                                    window.location.href = '{{ route('setting.staff.index') }}';
                                }, 800);
                            }
                        }
                    });
                }
            });
        });
    </script>
    @endcan
@endsection
