@extends('layouts.master')
@section('title', 'Create Payment')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, '') !!}
@endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Payment Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'expense.receipt.store', 'method' => 'POST', 'files' => true]) }}
                    @include('expense.receipt._inc.form')
                    <hr>
                    {!! form()->bsSubmit('Submit', 'btn btn-success waves-effect waves-light m-r-10') !!}
                    {!! form()->bsCancel('Cancel', 'expense.receipt.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
