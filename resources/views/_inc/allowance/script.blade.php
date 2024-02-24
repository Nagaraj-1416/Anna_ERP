<script>
    var allowanceForm = $('#allowanceForm'),
        allowanceFormBtn = $('#allowanceFormBtn'),
        allowanceModal = $('#allowanceModal');

    allowanceFormBtn.click(function () {
        allowanceForm.removeClass('hidden')
    });

    function cancelClick() {
        allowanceForm.addClass('hidden')
    }

    @if ($errors->has('amount') && !old('edit'))
    allowanceForm.removeClass('hidden');
    @endif

    @if(old('edit'))
    var data = @json(old());
    editAllowance(data);
    @endif

    function editAllowance(data) {
        console.log(data.assigned_date, data);
        allowanceModal.find('#assigned_date').val(data.assigned_date);
        allowanceModal.find('#amount').val(data.amount);
        allowanceModal.find('#notes').val(data.notes);
        allowanceModal.find('#allowance_id').val(data.id);
        $.each(allowanceModal.find('#is_active').find('option'), function (key, val) {
            if ($(val).val() === data.is_active) {
                $(val).attr('selected', true);
            }
        });
        var editRoute = '{{ route('allowance.edit', ['allowance' => 'ALLOWANCE']) }}';
        allowanceModal.find('form').removeAttr('action');
        allowanceModal.find('form').attr('action', editRoute.replace('ALLOWANCE', data.id));
        allowanceModal.modal({
            autofocus: false
        }).modal('show');
    }

    function hideModal() {
        allowanceModal.modal('hide')
    }
</script>