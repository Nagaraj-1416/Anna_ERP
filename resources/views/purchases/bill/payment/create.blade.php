<div id="paymentForm" class="hidden m-t-10">
    {!! form()->model($model, ['url' => route('purchase.payment.store', [$model]), 'method' => 'POST']) !!}
    <div class="form-body">
        <h3 class="box-title box-title-with-margin">Record Bill Payment</h3>
        <hr>
        @include('purchases.bill.payment._inc.form')
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left"></div>
                <div class="pull-right">
                    <button id="paymentSubmitBtn" type="Submit" class="btn btn-success"><i class="fa fa-check"></i>
                        Submit
                    </button>
                    <button type="Button" class="btn btn-inverse" id="cancelBtn"><i class="fa fa-remove"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{ form()->close() }}
</div>

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
            paymentMode: $('.payment-mode'),
            creditCardData: $('.credit-card-data'),
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
            } else if (paymentModeOnLoad === 'Special Discount') {
                $invoiceFormEl.chequeData.hide();
                $invoiceFormEl.directDepositData.hide();
                $invoiceFormEl.creditCardData.hide();
            } else if (paymentModeOnLoad === 'Exchange') {
                $invoiceFormEl.chequeData.hide();
                $invoiceFormEl.directDepositData.hide();
                $invoiceFormEl.creditCardData.hide();
            } else if (paymentModeOnLoad === 'Virtual') {
                $invoiceFormEl.chequeData.hide();
                $invoiceFormEl.directDepositData.hide();
                $invoiceFormEl.creditCardData.hide();
            }
        }
    </script>
@endsection
