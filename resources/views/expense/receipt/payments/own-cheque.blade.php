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
                            <div class="col-md-3">
                                {!! form()->bsText('cheque_no', 'Cheque no', null, ['placeholder' => 'cheque no', 'class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-3">
                                {!! form()->bsText('cheque_date', 'Cheque date', !old('_token') ? carbon()->toDateString() : null, ['placeholder' => 'cheque date', 'class' => 'form-control datepicker']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group required {{ $errors->has('cc_bank_id') ? 'has-danger' : '' }}">
                                    <label class="control-label">Written Bank</label>
                                    <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('cc_bank_id') ? 'error' : '' }}">
                                        <input name="cc_bank_id" id="cc_bank_id" type="hidden"
                                               value="{{ old('_token') ? old('cc_bank_id'): isset($payment) && $payment->bank_id ?? $payment->bank_id }}">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a bank</div>
                                        <div class="menu">
                                            @foreach(bankDropDown() as $key => $bank)
                                                <div class="item" data-value="{{ $key }}">{{ $bank }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('cc_bank_id') }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group required {{ $errors->has('paid_through') ? 'has-danger' : '' }}">
                                    <label class="control-label">Paid through</label>
                                    <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('paid_through') ? 'error' : '' }}">
                                        <input name="paid_through" id="paid_through" type="hidden"
                                               value="{{ old('_token') ? old('paid_through'): isset($payment) && $payment->paid_through ?? $payment->paid_through }}">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose an account</div>
                                        <div class="menu">
                                            @foreach(paidThroughAccByCompanyIdDropDown($expense->company_id) as $key => $account)
                                                <div class="item" data-value="{{ $key }}">{{ $account }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('paid_through') }}</p>
                                </div>
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
    <script>
        var dropDown = $('.drop-down');
        dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });
    </script>
@endsection