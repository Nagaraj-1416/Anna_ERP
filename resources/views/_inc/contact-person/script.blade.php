@php
    $formNames = ['first_name', 'last_name', 'salutation', 'full_name', 'phone','mobile', 'email', 'designation', 'department', 'is_active'];
    $oldValues = [];
@endphp
<script>
    var ContactPersonForm = $('#contact_person');
    var tableDataTemp = $('.table-data-temp table tbody');
    var addContactPersonBtn = $('#assign_contact_person');
    var addMoreDataRowBtn = $('#add_more_data_row');
    var cancelBtn = $('#cancel-btn');
    var defaultData = 1;
    var contactPersonCount = 0;
    var $errors;
    @if(isset($errors))
        $errors = @json($errors->getMessages());
    @endif
    @if(old('_token'))
        @php
            foreach ($formNames as $formName){
                if(old($formName)){
                    $oldValues[$formName] = old($formName);
                }
            }
        @endphp
        var $oldValues = [{!! json_encode($oldValues) !!}][0];
        if ($oldValues['first_name']) {
            addContactPersonTable(false);
        }
        $.each($oldValues['first_name'], function (value) {
            addContactPersonList($oldValues);
        });
    @endif

    addContactPersonBtn.click(function () {
        addContactPersonTable(true);
    });

    function addContactPersonTable(dataGenerate) {
        ContactPersonForm.removeClass('hidden');
        if (dataGenerate && !contactPersonCount) {
            for (i = 0; i < defaultData; i++) {
                addContactPersonList();
            }
        }
    }

    addMoreDataRowBtn.click(function () {
        addContactPersonList();
    });

    function addContactPersonList($value) {
        contactPersonCount++;
        if (contactPersonCount > defaultData) {
            $('.remove_row_btn').removeClass('hidden');
        }
        var tBody = ContactPersonForm.find('#personTable').find('tbody');
        var clonedDataTemp = tableDataTemp.clone();
        clonedDataTemp.html(clonedDataTemp.html().replace(/CPD/g, contactPersonCount));
        if($value){
            $.each($value, function (key , value) {
                var elem = '#'+key+ contactPersonCount;
                if(key === 'salutation'){
                    $.each(clonedDataTemp.find(elem).find('option'), function (key, val) {
                        if($(val).val() === value[contactPersonCount]){
                            $(val).attr('selected', true);
                        }
                    })
                }else{
                    clonedDataTemp.find(elem).attr('value', value[contactPersonCount])
                }
            })
        }
        if($errors){
            $.each($errors, function (key , value) {
                var elemName = key.split('.');
                var elemForError = '#'+elemName[0]+elemName[1];
                clonedDataTemp.find(elemForError).parent().addClass('has-danger');
            })
        }
        tBody.append(clonedDataTemp.html());
    }

    function removeRow(row) {
        $(row).parent().parent().remove();
        contactPersonCount--;
        if (contactPersonCount <= defaultData) {
            $('.remove_row_btn').addClass('hidden');
        }
    }

    cancelBtn.click(function () {
        contactPersonCount = 0;
        ContactPersonForm.addClass('hidden');
        ContactPersonForm.find('#personTable').find('tbody').find('tr').remove();
    });
</script>