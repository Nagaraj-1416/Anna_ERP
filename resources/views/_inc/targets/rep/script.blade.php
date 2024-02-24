@php
    $formNames = ['type', 'start_date', 'end_date', 'target'];
    $oldValues = [];
@endphp
<script src="{{ asset('js/vendor/form.js') }}"></script>
<script>
    var targetForm = $('#targetForm');
    var tableDataTemp = $('.table-data-temp table tbody');
    var targetCount = 0;
    var defaultData = 1;
    var $errors = [];
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

    $.each($oldValues['start_date'], function (value) {
        if ($oldValues['start_date']) {
            addTargetTable(false);
        }
        addNewRecord($oldValues);
    });
    @endif

    $('#assign_rep_target').click(function () {
        addTargetTable(true);
    });

    function addTargetTable(dataGenerate) {
        targetForm.parent().parent().removeClass('hidden');
        if (dataGenerate && !targetCount) {
            for (i = 0; i < defaultData; i++) {
                addNewRecord();
            }
        }
    }

    function addNewRecord($value) {
        targetCount++;
        if (targetCount > defaultData) {
            $('.remove_row_btn').removeClass('hidden');
        }
        var tBody = targetForm.find('#personTable').find('tbody');
        var clonedDataTemp = tableDataTemp.clone();
        clonedDataTemp.html(clonedDataTemp.html().replace(/RT/g, targetCount));
        var startDate = null;
        var endDate = null;
        if ($value) {
            $.each($value, function (key, value) {
                var elem = '#' + key + targetCount;
                if (key === 'type') {
                    $.each(clonedDataTemp.find(elem).find('option'), function (key, val) {
                        if ($(val).val() === value[targetCount]) {
                            $(val).attr('selected', true);
                        }
                    })
                } else if (key === 'start_date') {
                    startDate = value[targetCount];
                } else if (key === 'end_date') {
                    endDate = value[targetCount];
                } else {
                    clonedDataTemp.find(elem).attr('value', value[targetCount]);
                }
            })
        }
        var trError = false;
        var errorInput = '';
        if ($errors) {
            $.each($errors, function (key, value) {
                var elemName = key.split('.');
                var elemForError = '#' + elemName[0] + elemName[1];
                var elemForErrorMessage = '#' + elemName[0] + elemName[1] + '_help';
                clonedDataTemp.find(elemForError).parent().addClass('has-danger');
                if(parseInt(elemName[1]) === targetCount && value[0] === 'date_range'){
                    clonedDataTemp.find(elemForErrorMessage).text('A target is already defined for this chosen period, please check the available targets and pick another period to continue.');
                }
            })
        }
        tBody.append(clonedDataTemp.html());
        var startDateElem = '#start_date' + targetCount;
        tBody.find(startDateElem).datepicker("setDate", startDate ? new Date(startDate) : new Date()).on('changeDate', function (e) {
            addEndDatepicker(tBody, e.date, targetCount, endDate);
        });
        addEndDatepicker(tBody, startDate ? new Date(startDate) : new Date(), targetCount, endDate)
    }

    function addEndDatepicker(tBody, date, targetCount, endDate) {
        var elem = '#end_date' + targetCount;
        tBody.find(elem).datepicker('clearDates');
        tBody.find(elem).datepicker('update', endDate ? new Date(endDate) : '');
        tBody.find(elem).datepicker('destroy');
        tBody.find(elem).datepicker({
            startDate: date ? date : new Date()
        });
    }

    function removeRow(row) {
        $(row).parent().parent().remove();
        targetCount--;
        if (targetCount <= defaultData) {
            $('.remove_row_btn').addClass('hidden');
        }
    }
    
    function hiddenForm() {
        targetForm.parent().parent().addClass('hidden');
    }
</script>