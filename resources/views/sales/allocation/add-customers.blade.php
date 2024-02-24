@extends('layouts.master')
@section('title', 'Add Customers')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Add Customers to Allocation</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($allocation, ['url' => route('sales.allocation.store.customer', $allocation), 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><b>Allocation from :</b> {{ $allocation->from_date }}</p>
                                <input type="hidden" name="allocation_start" value="{{ $allocation->from_date }}">
                            </div>
                            <div class="col-md-8">
                                <p><b>Allocation to :</b> {{ $allocation->to_date }}</p>
                                <input type="hidden" name="allocation_end" value="{{ $allocation->to_date }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p><b>Allocated Route :</b> {{ $allocation->route->name }}
                                </p>
                            </div>
                            <div class="col-md-8">
                                <p><b>Allocated Vehicle :</b> {{ $allocation->salesLocation->name }}
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p><b>Allocated Rep :</b> {{ $allocation->rep->name.' ('.$allocation->rep->code.')' }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Allocated Driver :</b> {{ $allocation->driver->short_name.' ('.$allocation->driver->code.')' }}
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
                            <div class="col-md-12">
                                <div class="form-group required {{ $errors->has('customers') ? 'has-danger' : '' }}">
                                    <label class="control-label">Customers</label>
                                    <div class="ui fluid search selection multiple dropdown customers-drop-down {{ $errors->has('customers') ? 'error' : '' }}">
                                        <input name="customers" type="hidden" value="">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose customers</div>
                                        <div class="menu">
                                            @foreach($customers as $key => $customer)
                                                <div class="item" data-value="{{ $key }}">{{ $customer }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('customers') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! form()->bsSubmit('Submit', 'btn btn-success waves-effect waves-light m-r-10', 'Submit', 'submit') !!}
                    {!! form()->bsCancel('Cancel', 'sales.allocation.sheet', [$allocation]) !!}
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
        var repDropDown = $('.customers-drop-down');
        repDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });
    </script>
@endsection
