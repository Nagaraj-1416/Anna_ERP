<script src="{{ asset('js/vendor/form.js') }}"></script>
<script>
    $paymentFormEl = {
        recordPayment: $('#recordPayment'),
        paymentForm: $('#paymentForm'),
        cancelBtn: $('#cancelBtn')
    };

    $paymentFormEl.recordPayment.click(function (e) {
        e.preventDefault();
        $paymentFormEl.paymentForm.fadeIn();
    });

    $paymentFormEl.cancelBtn.click(function (e) {
        e.preventDefault();
        $paymentFormEl.paymentForm.fadeOut();
    });

    @if ($errors->has('payment'))
        $paymentFormEl.paymentForm.fadeIn('hidden');
    @endif

    @if ($errors->has('payment_date'))
        $paymentFormEl.paymentForm.fadeIn('hidden');
    @endif

    var dropDown = $('.drop-down');
    dropDown.dropdown('setting', {
        forceSelection: false,
        saveRemoteData: false
    });
</script>