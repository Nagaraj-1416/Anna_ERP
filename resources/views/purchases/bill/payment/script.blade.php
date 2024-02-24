<script src="{{ asset('js/vendor/form.js') }}"></script>
<script>
    $paymentFormEl = {
        recordPayment: $('#recordPayment'),
        paymentForm: $('#paymentForm'),
        cancelBtn: $('#cancelBtn'),
        editBtn: $('.edit-payment-btn')
    };

    $paymentFormEl.recordPayment.click(function (e) {
        e.preventDefault();
        $paymentFormEl.paymentForm.fadeIn();
    });

    $paymentFormEl.cancelBtn.click(function (e) {
        e.preventDefault();
        $paymentFormEl.paymentForm.fadeOut('hidden');
        $paymentFormEl.editBtn.removeClass('hidden');
    });

    @if ($errors->has('payment'))
        $paymentFormEl.paymentForm.removeClass('hidden');
    @endif

    @if ($errors->has('payment_date'))
        $paymentFormEl.paymentForm.removeClass('hidden');
    @endif

    var dropDown = $('.drop-down');
    dropDown.dropdown('setting', {
        forceSelection: false,
        saveRemoteData: false
    });
</script>