<script>
    var editBtn = $('.edit-payment-btn');
    var deleteBtn = $('.delete-payment-btn');
    var form = $("#paymentForm");
    var paymentSubmitBtn = $("#paymentSubmitBtn");
    var $invoiceFormEl = {
        chequeData: $('.cheque-data'),
        directDepositData: $('.direct-deposit-data'),
        paymentMode: $('.payment-mode'),
        creditCardData: $('.credit-card-data'),
    };
    var route = '{{ route('sales.payment.edit', ['payment' => 'PAYMENT']) }}';

    var update = '{{ route('sales.payment.update', ['payment' => 'PAYMENT']) }}';
    var editId = 0;
    var editable = false;

    editBtn.click(function () {
        editable = true;
        form.find('.box-title').text('Update Payment');
        editId = $(this).data('id');
        setValue(editId);
        form.fadeIn();
        editBtn.addClass('hidden');
        form.find('.form-group').removeClass('has-danger');
        form.find('.form-control-feedback').text('');
        $('html, body').animate({
            scrollTop: $('body').offset().top
        }, 1000);
    });

    function setValue(editId) {
        if (editId) {
            $.ajax(route.replace('PAYMENT', editId), {
                method: 'GET'
            }).done(function (response) {
                checkBoxChecked(response.payment_mode);
                $('[name="payment_type"]').removeAttr('checked');
                $('[name="payment_mode"]').removeAttr('checked');
                var chequeType = '.' + getId(response.cheque_type);
                var PaymentTypeCheckbox = '.' + getId(response.payment_type);
                var PaymentModeCheckbox = '.' + getId(response.payment_mode);
                $(PaymentTypeCheckbox).attr('checked', true);
                $(PaymentModeCheckbox).attr('checked', true);
                $(chequeType).attr('checked', true);
                $.each(response, function (key, val) {
                    if (key === 'notes') key = 'payment_notes';
                    var id = '#' + key;
                    if (key === 'bank' && val) {
                        if (response.payment_mode === 'Cheque') {
                            $('#cheque_bank_id').parent().dropdown('set text', val.name).dropdown('set value', val.id);
                        } else if (response.payment_mode === 'Direct Deposit') {
                            $('#dd_bank_id').parent().dropdown('set text', val.name).dropdown('set value', val.id);
                        }
                    }

                    if (key === 'deposited_to') {
                        $(id).parent().dropdown('set text', val.name);
                        $(id).val(val.id)
                    } else {
                        $(id).val(val)
                    }

                });
            })
        }

        paymentSubmitBtn.click(function (e) {
            if (editable) {
                e.preventDefault();
                var data = getVal();
                $.ajaxSetup({
                    headers:
                        {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });
                $.ajax({
                    method: "patch",
                    data: data,
                    url: update.replace('PAYMENT', editId)
                }).done(function () {
                    window.location.reload()
                }).fail(function (response) {
                    if (response.hasOwnProperty('responseJSON') && response.responseJSON.hasOwnProperty('errors')) {
                        var errors = response.responseJSON.errors;
                        $.each(errors, function (index, value) {
                            var elm = '#' + index;
                            $(elm).parent().addClass('has-danger');
                            $(elm).parent().find('.form-control-feedback').text(value);
                        })
                    }
                });
            }
        })
    }

    function getId(text) {
        var res = text.replace(' ', '-');
        return res.toLowerCase();
    }

    function getVal() {
        return {
            payment: $('#payment').val(),
            payment_date: $('#payment_date').val(),
            deposited_to: $('#deposited_to').val(),
            notes: $('#payment_notes').val(),
            payment_type: getChecked($('[name="payment_type"]')),
            payment_mode: getChecked($('[name="payment_mode"]')),
            cheque_type: getChecked($('[name="cheque_type"]')),
            cheque_no: $('#cheque_no').val(),
            cheque_date: $('#cheque_date').val(),
            cheque_bank_id: $('#cheque_bank_id').parent().dropdown('get value'),
            account_no: $('#account_no').val(),
            deposited_date: $('#deposited_date').val(),
            dd_bank_id: $('#dd_bank_id').parent().dropdown('get value'),
            card_holder_name: $('#card_holder_name').val(),
            card_no: $('#card_no').val(),
            expiry_date: $('#expiry_date').val(),
        }
    }

    function getChecked(elem) {
        var name = '';
        $.each(elem, function (val, ind) {
            if ($(ind).is(':checked')) {
                name = $(ind).val()
            }
        });
        return name;
    }

    $('#cancelBtn').click(function (e) {
        e.preventDefault();
        $('input').val('');
        $('.drop-down').dropdown('set text', null).dropdown('set value', null);
        form.find('.box-title').text('Record Payment');
        paymentSubmitBtn.text('Submit');
        paymentSubmitBtn.removeClass('btn-primary').addClass('btn-success');
        editable = false;
        editBtn.removeClass('hidden');
    });


    function checkBoxChecked(paymentMode) {
        if (paymentMode === 'Cheque') {
            $invoiceFormEl.chequeData.show();
            $invoiceFormEl.directDepositData.hide();
            $invoiceFormEl.creditCardData.hide();
        } else if (paymentMode === 'Direct Deposit') {
            $invoiceFormEl.chequeData.hide();
            $invoiceFormEl.directDepositData.show();
            $invoiceFormEl.creditCardData.hide();
        } else if (paymentMode === 'Credit Card') {
            $invoiceFormEl.chequeData.hide();
            $invoiceFormEl.directDepositData.hide();
            $invoiceFormEl.creditCardData.show();
        } else if (paymentMode === 'Cash') {
            $invoiceFormEl.chequeData.hide();
            $invoiceFormEl.directDepositData.hide();
            $invoiceFormEl.creditCardData.hide();
        }
    }

    deleteBtn.click(function () {
        var id = $(this).data('id');
        var deleteUrl = '{{ route('sales.payment.delete', [ 'payment'=>'ID']) }}';
        deleteUrl = deleteUrl.replace('ID', id);
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this action!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DB2828',
            confirmButtonText: 'Yes, Delete!'
        }).then(function (isConfirm) {
            if (isConfirm.value) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {'_token': '{{ csrf_token() }}'},
                    success: function (result) {
                        swal(
                            'Deleted!',
                            'Payment deleted successfully!',
                            'success'
                        );
                        setTimeout(location.reload(), 300);
                    }
                });
            }
        });
    })
</script>