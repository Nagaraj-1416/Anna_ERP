@extends('layouts.master')
@section('title', 'Edit Report')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Expense') !!}
@endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Report Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($report, ['url' => route('expense.reports.update', $report), 'method' => 'PATCH']) !!}
                    @include('expense.reports._inc.form')
                    <hr>
                    {!! form()->bsSubmit('Update', 'btn btn-success waves-effect waves-light m-r-10') !!}
                    {!! form()->bsCancel('Cancel', 'expense.reports.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
