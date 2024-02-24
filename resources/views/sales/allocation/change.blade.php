@extends('layouts.master')
@section('title', 'Change Rep / Driver / Labour')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Change Rep / Driver / Labour</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($allocation, ['url' => route('sales.allocation.update.actors', $allocation), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><b>Allocation from :</b> {{ $allocation->from_date }}</p>
                            </div>
                            <div class="col-md-8">
                                <p><b>Allocation to :</b> {{ $allocation->to_date }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p><b>Allocated Rep :</b>
                                    <a target="_blank"
                                       href="{{ route('setting.rep.show', [$allocation->rep]) }}">
                                        {{ $allocation->rep->name.' ('.$allocation->rep->code.')' }}
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Allocated Driver :</b>
                                    <a target="_blank"
                                       href="{{ route('setting.staff.show', [$allocation->driver]) }}">
                                        {{ $allocation->driver->short_name.' ('.$allocation->driver->code.')' }}
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Labours :</b>
                                    @foreach(getAllocationLabours($allocation) as $labour)
                                        {{ $labour->short_name }}@if(!$loop->last), @endif
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group required {{ $errors->has('rep_id') ? 'has-danger' : '' }}">
                                    <label class="control-label">Available Reps</label>
                                    <div class="ui fluid search normal selection dropdown rep-drop-down {{ $errors->has('rep_id') ? 'error' : '' }}">
                                        <input name="rep_id" type="hidden" value="{{ old('_token') ? old('rep_id'): '' }}">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a sales rep</div>
                                        <div class="menu">
                                            @foreach(notAllocatedRepDropDown($allocation) as $key => $rep)
                                                <div class="item" data-value="{{ $key }}">{{ $rep }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('rep_id') }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group required {{ $errors->has('driver_id') ? 'has-danger' : '' }}">
                                    <label class="control-label">Available Drivers</label>
                                    <div class="ui fluid search normal selection dropdown driver-drop-down {{ $errors->has('driver_id') ? 'error' : '' }}">
                                        <input name="driver_id" type="hidden" value="{{ old('_token') ? old('driver_id'): '' }}">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a driver</div>
                                        <div class="menu">
                                            @foreach(notAllocatedDriverDropDown($allocation) as $key => $rep)
                                                <div class="item" data-value="{{ $key }}">{{ $rep }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('driver_id') }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group required {{ $errors->has('labour_id') ? 'has-danger' : '' }}">
                                    <label class="control-label">Available Labours</label>
                                    <div class="ui fluid search normal selection multiple dropdown labour-drop-down {{ $errors->has('labour_id') ? 'error' : '' }}">
                                        <input name="labour_id" type="hidden" value="{{ old('_token') ? old('labour_id'): '' }}">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose labours</div>
                                        <div class="menu">
                                            @foreach(notAllocatedLabourDropDown($allocation) as $key => $rep)
                                                <div class="item" data-value="{{ $key }}">{{ $rep }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('labour_id') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! form()->bsSubmit('Change', 'btn btn-success waves-effect waves-light m-r-10', 'Change', 'submit') !!}
                    {!! form()->bsCancel('Cancel', 'sales.allocation.show', [$allocation]) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        var repDropDown = $('.rep-drop-down');
        repDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        var driverDropDown = $('.driver-drop-down');
        driverDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        var labourDropDown = $('.labour-drop-down');
        labourDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });
    </script>
@endsection
