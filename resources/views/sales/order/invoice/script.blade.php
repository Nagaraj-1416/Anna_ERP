<script src="{{ asset('js/vendor/form.js') }}"></script>
<script>
    $invFormEl = {
        createInv: $('#createInv'),
        invoiceForm: $('#invoiceForm'),
        cancelBtn: $('#cancelBtn')
    };

    $invFormEl.createInv.click(function (e) {
        e.preventDefault();
        $invFormEl.invoiceForm.removeClass('hidden');
    });

    $invFormEl.cancelBtn.click(function (e) {
        e.preventDefault();
        $invFormEl.invoiceForm.addClass('hidden');
    });

    @if ($errors->has('invoice_date'))
        $invFormEl.invoiceForm.removeClass('hidden');
    @endif

    @if ($errors->has('due_date'))
        $invFormEl.invoiceForm.removeClass('hidden');
    @endif

    @if ($errors->has('amount'))
        $invFormEl.invoiceForm.removeClass('hidden');
    @endif

</script>