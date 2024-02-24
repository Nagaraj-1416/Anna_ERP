@extends('layouts.master')
@section('title', 'Add Payment')
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
                    {!! form()->model($expense, ['url' => route('expense.receipt.store.payment', [$expense, $mode]), 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-md-3">
                            {!! form()->bsText('payment', 'Payment', null, ['placeholder' => 'payment']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! form()->bsTextarea('notes', 'Remarks', null, ['placeholder' => 'enter payment related remarks here...', 'cols' => 100, 'rows' => 3]) !!}
                        </div>
                    </div>
                    <hr>
                    {!! form()->bsSubmit('Add Payment', 'btn btn-success waves-effect waves-light m-r-10') !!}
                    {!! form()->bsCancel('Cancel', 'expense.receipt.show', $expense) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
@endsection