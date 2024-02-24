@extends('layouts.master')
@section('title', 'Complete Allocation')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Complete Allocation</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($allocation, ['url' => route('sales.allocation.do.complete', $allocation), 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="box-title">Expense Details</h4>
                                <hr>
                                {!! form()->bsText('allowance', 'Allocated sales allowance', $allocation->allowance, ['placeholder' => '', 'readonly']) !!}
                            </div>
                            <div class="col-md-6">
                                <h4 class="box-title">ODO meter readings</h4>
                                <hr>
                                {!! form()->bsText('end_odo_reading', 'End reading '.'( Start reading: '.$allocation->odoMeterReading->starts_at.' )', null, ['placeholder' => 'enter ODO meter end reading']) !!}
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! form()->bsSubmit('Complete', 'btn btn-success waves-effect waves-light m-r-10', 'Complete', 'submit') !!}
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

    </script>
@endsection
