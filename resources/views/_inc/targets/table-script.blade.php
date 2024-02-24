<script>
    var targetModal = $('#target_modal');
    var targetGetRoute = '{{ $getRoute }}';
    var form = $('#targetEditForm');
    var editRoutes = '{{ $editRoute }}';
    var $errors;
    @if(isset($errors))
        $errors = @json($errors->getMessages());
            @endif
            @if(old('_token'))
            @php
                $oldValues = [];
                    $formNames = ['is_active', 'type', 'start_date', 'end_date', 'target', 'achieved', 'target_id'];
                        foreach ($formNames as $formName){
                            if(old($formName)){
                                $oldValues[$formName] = old($formName);
                            }
                        }
            @endphp
    var oldData = @json($oldValues);
    if (oldData.target_id) {
        setDataForModal(oldData, oldData.target_id);
        modalOpen();
    }

    @endif
    function editTarget(attr) {
        modalOpen();
        var target = $(attr).data('id');
        getDataOfTarget(target);
    }

    function modalOpen() {
        targetModal.modal({
            autofocus: false,
            closable: false,
        }).modal('show');
    }

    function getDataOfTarget(target) {
        $.get(targetGetRoute.replace('TARGET', target), function (response) {
            if (response) {
                setDataForModal(response, target);
            }
        });
    }

    function setDataForModal(response, id) {
        form.removeAttr('action');
        form.attr('action', editRoutes.replace('TARGET', id));
        $('#targetId').val(id);
        $.each($('#type').find('option'), function (key, val) {
            if ($(val).val() === response.type) {
                $(val).attr('selected', true);
            }
        });
        $.each($('#is_active').find('option'), function (key, val) {
            if ($(val).val() === response.is_active) {
                $(val).attr('selected', true);
            }
        });
        var startDateElem = $('#start_date');
        startDateElem.val(response.start_date);
        if ($errors.start_date) {
            startDateElem.parent().addClass('has-danger');
            startDateElem.parent().find('.form-control-feedback').text($errors.start_date)
        }
        var endDateElem = $('#end_date');
        endDateElem.val(response.end_date);
        if ($errors.end_date) {
            endDateElem.parent().addClass('has-danger');
            endDateElem.parent().find('.form-control-feedback').text($errors.end_date)
        }
        var targetElem = $('#target');
        targetElem.val(response.target);
        if ($errors.target) {
            targetElem.parent().addClass('has-danger');
            targetElem.parent().find('.form-control-feedback').text($errors.target)
        }
    }

    /**
     * Cancel button click event
     */
    function cancelEdit() {
        targetModal.modal('hide')
    }
</script>