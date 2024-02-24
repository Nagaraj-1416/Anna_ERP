@extends('layouts.master')
@section('title', 'Record Payment')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Payment Details</h3>
                </div>
                <div class="card-body wizard-content">
                    @include('sales.order._inc.steps')
                    <hr class="hr-dark">
                    {!! form()->model($order, ['url' => route('sales.order.record.payment', [$order, $invoice]), 'method' => 'POST']) !!}

                        <div class="row">
                            <div class="col-md-3">
                                <div class="alert alert-info">
                                    <h5 class="text-info">
                                        <i class="ti-receipt"></i> Invoiced Amount
                                        - {{ number_format($invoice->amount, 2) }}
                                    </h5>
                                </div>
                            </div>
                        </div>

                        @include('sales.invoice.payment._inc.form')
                        <hr>
                        <div class="clearfix">
                            <div class="pull-left">
                                {!! form()->bsCancel('Cancel', 'sales.invoice.show', [$order]) !!}
                            </div>
                            <div class="pull-right">
                                {!! form()->bsSubmit('Record Payment', 'btn btn-success waves-effect waves-light m-r-10', 'Save', 'submit') !!}
                            </div>
                        </div>

                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        $invoiceFormEl = {
            chequeData: $('.cheque-data'),
            directDepositData: $('.direct-deposit-data'),
            creditCardData: $('.credit-card-data'),
            paymentMode: $('.payment-mode')
        };

        $invoiceFormEl.paymentMode.change(function (e) {
            e.preventDefault();
            handlePaymentMode($(this).val());
        });

        @if(old('_token'))
        handlePaymentMode('{{old('payment_mode')}}');
                @else
        var paymentModeOnLoad = $invoiceFormEl.paymentMode.val();
        handlePaymentMode(paymentModeOnLoad);
        @endif


        function handlePaymentMode(paymentModeOnLoad) {
            if (paymentModeOnLoad === 'Cheque') {
                $invoiceFormEl.chequeData.show();
                $invoiceFormEl.directDepositData.hide();
                $invoiceFormEl.creditCardData.hide();
            } else if (paymentModeOnLoad === 'Direct Deposit') {
                $invoiceFormEl.chequeData.hide();
                $invoiceFormEl.directDepositData.show();
                $invoiceFormEl.creditCardData.hide();
            } else if (paymentModeOnLoad === 'Credit Card') {
                $invoiceFormEl.chequeData.hide();
                $invoiceFormEl.directDepositData.hide();
                $invoiceFormEl.creditCardData.show();
            } else if (paymentModeOnLoad === 'Cash') {
                $invoiceFormEl.chequeData.hide();
                $invoiceFormEl.directDepositData.hide();
                $invoiceFormEl.creditCardData.hide();
            }
        }

        var dropDown = $('.drop-down');
        dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

    </script>
@endsection