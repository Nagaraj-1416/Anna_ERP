<script src="{{ asset('js/vendor/form.js') }}"></script>
<script>
    $billFormEl = {
        createBill: $('#createBill'),
        billForm: $('#billForm'),
        cancelBtn: $('#cancelBtn')
    };

    $billFormEl.createBill.click(function (e) {
        e.preventDefault();
        $billFormEl.billForm.removeClass('hidden');
    });

    $billFormEl.cancelBtn.click(function (e) {
        e.preventDefault();
        $billFormEl.billForm.addClass('hidden');
    });

    @if ($errors->has('bill_date'))
        $billFormEl.billForm.removeClass('hidden');
    @endif

    @if ($errors->has('due_date'))
        $billFormEl.billForm.removeClass('hidden');
    @endif

    @if ($errors->has('amount'))
        $billFormEl.billForm.removeClass('hidden');
    @endif

</script>