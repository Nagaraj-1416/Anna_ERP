@extends('layouts.master')
@section('title', 'Create Report')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Expense') !!}
@endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Report Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'expense.reports.store', 'method' => 'POST', 'files' => true]) }}
                    @include('expense.reports._inc.form')
                    <hr>
                    {!! form()->bsSubmit('Submit', 'btn btn-success waves-effect waves-light m-r-10') !!}
                    {!! form()->bsCancel('Cancel', 'expense.reports.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
