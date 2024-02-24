@extends('layouts.master')
@section('title', 'Add Expense')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Add Expense</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($allocation, ['url' => route('sales.allocation.store.expense', $allocation), 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
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
                                <div class="form-group required {{ $errors->has('type_id') ? 'has-danger' : '' }}">
                                    <label class="control-label">Expense type</label>
                                    <div class="ui fluid search normal selection dropdown exp-type-drop-down {{ $errors->has('type_id') ? 'error' : '' }}">
                                        <input name="type_id" type="hidden" value="{{ old('_token') ? old('type_id'): '' }}">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a expense type</div>
                                        <div class="menu">
                                            @foreach(expenseTypesDropDown() as $key => $type)
                                                <div class="item" data-value="{{ $key }}">{{ $type }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('type_id') }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                {!! form()->bsText('expense_date', 'Expense date', null, ['placeholder' => 'pick expense date', 'class' => 'form-control datepicker']) !!}
                            </div>
                            <div class="col-md-4">
                                {!! form()->bsText('amount', 'Expense amount', null, ['placeholder' => 'enter expense amount', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                {!! form()->bsText('liters', 'Fuel liters', null, ['placeholder' => 'enter liters', 'class' => 'form-control'], false) !!}
                            </div>
                            <div class="col-md-4">
                                {!! form()->bsText('odometer', 'ODO Meter reading when was filling', null, ['placeholder' => 'enter ODO meter reading', 'class' => 'form-control'], false) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md">
                                {!! form()->bsTextarea('notes', 'Notes', '', ['placeholder' => 'enter expense related notes here...', 'rows' => '3'], false) !!}
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
        var repDropDown = $('.exp-type-drop-down');
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
