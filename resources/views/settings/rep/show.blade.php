@extends('layouts.master')
@section('title', 'Rep Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Settings') !!}
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline-inverse">
            <div class="card-header">
                <h4 class="m-b-0 text-white">{{ $rep->code }}</h4>
            </div>
            <div class="card-body">
                <!-- action buttons -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                            <button class="btn waves-effect waves-light btn-info btn-sm" id="assign_rep_target">
                                <i class="fa fa-money"></i> Assign Targets
                            </button>
                            @if(!$rep->vehicle_id)
                                <button class="btn waves-effect waves-light btn-warning btn-sm" id="assign_vehicle">
                                    <i class="fa fa-truck"></i> Assign Vehicle
                                </button>
                            @endif
                            @if($rep->vehicle_id)
                                <button class="btn waves-effect waves-light btn-warning btn-sm" id="block_vehicle">
                                    <i class="fa fa-truck"></i> Block Vehicle
                                </button>
                                <button class="btn waves-effect waves-light btn-danger btn-sm" id="revoke_vehicle">
                                    <i class="fa fa-truck"></i> Revoke Vehicle
                                </button>
                            @endif
                        </div>
                        <div class="pull-right"></div>
                    </div>
                </div>
                @include('_inc.vehicle-rep.attach', [
                'name' => 'vehicle',
                'model' => $rep,
                'searchModal' => 'Vehicle',
                'relation' => 'vehicles'
                ])
                @include('_inc.targets.rep.index', ['rep' => $rep])
                <div class="row custom-top-margin">
                    <div class="col-md-9">
                        <div class="card card-body">
                            <h3>
                                <b>{{ $rep->name }}</b>
                                <span class="pull-right text-muted">
                                @if($rep->is_active == 'Yes')
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
                                    <a target="_blank" href="{{ route('setting.staff.show', [$staff]) }}">For More Staff Detail</a>
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-6 b-r"> <strong>Short name</strong>
                                            <br>
                                            <p class="text-muted">{{ $staff->short_name or 'None' }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6"> <strong>Joined date</strong>
                                            <br>
                                            <p class="text-muted">{{ $staff->joined_date or 'None' }}</p>
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
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3><b>SALES</b> <span class="pull-right">Total Sales: 0</span></h3>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table color-table muted-table">
                                        <thead>
                                        <tr>
                                            <th>Bill no</th>
                                            <th>Bill date</th>
                                            <th>Due date</th>
                                            <th>Status</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-right">Paid</th>
                                            <th class="text-right">Balance</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <a target="_blank" href="#">BL000001</a>
                                            </td>
                                            <td>Nigam</td>
                                            <td>Eichmann</td>
                                            <td>@Sonu</td>
                                            <td class="text-right text-info">$12000</td>
                                            <td class="text-right text-success">$12000</td>
                                            <td class="text-right text-warning">$12000</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3><b>VEHICLE HISTORIES</b> <span class="pull-right">Total Histories: 0</span></h3>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table color-table muted-table">
                                        <thead>
                                        <tr>
                                            <th>Bill no</th>
                                            <th>Bill date</th>
                                            <th>Due date</th>
                                            <th>Status</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-right">Paid</th>
                                            <th class="text-right">Balance</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <a target="_blank" href="#">BL000001</a>
                                            </td>
                                            <td>Nigam</td>
                                            <td>Eichmann</td>
                                            <td>@Sonu</td>
                                            <td class="text-right text-info">$12000</td>
                                            <td class="text-right text-success">$12000</td>
                                            <td class="text-right text-warning">$12000</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @include('_inc.targets.table', ['modal' => $rep])
                    </div>
                    <div class="col-md-3">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Rep Credit Limit</h4>
                                <hr>
                                <div>
                                    <h3 class="card-title"><b>{{ number_format($rep->cl_amount, 2) }}</b></h3>
                                    <h6 class="card-subtitle">CL used notification at <b>{{ $rep->cl_notify_rate }}%</b></h6>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Rep Targets Summary</h4>
                                <hr>
                                @if($rep->vehicle)
                                <div>
                                    <h3 class="card-title">
                                        <b>
                                            <a target="_blank" href="{{ route('setting.vehicle.show', [$rep->vehicle]) }}">
                                                {{$rep->vehicle->vehicle_no ?? ''}}
                                            </a>
                                        </b>
                                    </h3>
                                    <h6 class="card-subtitle">Assigned Vehicle</h6>
                                </div>
                                <hr>
                                @endif
                                <div>
                                    <h3 class="card-title"><b>$250,000</b></h3>
                                    <h6 class="card-subtitle">Total Assigned Target</h6>
                                </div>
                                <div class="custom-top-margin">
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 45%; height:10px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <h4>5486</h4>
                                        <h6 class="text-muted text-info">Total Sales</h6>
                                    </div>
                                    <div class="col-4">
                                        <h4>$987</h4>
                                        <h6 class="text-muted text-success">Target Achieved</h6>
                                    </div>
                                    <div class="col-4">
                                        <h4>$987</h4>
                                        <h6 class="text-muted text-warning">Target Balance</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- recent comments -->
                        <div class="card">
                            <div class="card-body">
                                @include('general.comment.index', ['model' => $rep])
                            </div>
                        </div>

                        <!-- recent audit logs -->
                        <div class="card">
                            <div class="card-body">
                                @include('general.log.index', ['model' => $rep, 'modelName' => 'Rep'])
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('_inc.relation-table.revoke', ['rep' => $rep])
@include('_inc.relation-table.block', ['rep' => $rep])

@section('script')
    @include('_inc.targets.rep.script')
    @include('general.comment.script', ['modelId' => $rep->id])
    @include('_inc.targets.table-script', [
    'modal' => $rep,
    'getRoute' => route('setting.rep.target.get', ['rep' => $rep, 'target' => 'TARGET']),
    'editRoute' => route('setting.rep.target.edit', ['rep' => $rep, 'target' => 'TARGET'])
    ])
    @parent
    <script>
        var revokeModal = $('#rb_modal');
        revokeModal.modal({
            autofocus:false,
            closable:false,
        });
        $('#revoke_vehicle').click(function () {
            revokeModal.modal('show');
        });
        @if ($errors->has('date'))
        revokeModal.modal('show');
        @endif
        var blockModal = $('#block_modal');
        blockModal.modal({
            autofocus:false,
            closable:false,
        });
        $('#block_vehicle').click(function () {
            blockModal.modal('show');
        });
        @if ($errors->has('block_date'))
                blockModal.modal('show');
        @endif
        $('.cancelBtn').click(function () {
            blockModal.modal('hide');
            revokeModal.modal('hide');
        })
    </script>
@endsection
