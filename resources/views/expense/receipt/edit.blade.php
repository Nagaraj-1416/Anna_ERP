@extends('layouts.master')
@section('title', 'Edit Payment')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Expense') !!}
@endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Payment Details</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($expense, ['url' => route('expense.receipt.update', $expense), 'method' => 'PATCH']) !!}
                    @include('expense.receipt._inc.form-edit')
                    <hr>
                    {!! form()->bsSubmit('Update', 'btn btn-success waves-effect waves-light m-r-10') !!}
                    {!! form()->bsCancel('Cancel', 'expense.receipt.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
