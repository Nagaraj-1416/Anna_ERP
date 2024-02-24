<script>

    $('.from-year-datepicker').datepicker({
        format: 'yyyy',
        endDate: new Date(),
        minViewMode: 'years',
    });

    var date = {
        fromMonth: $('.from-month-datepicker'),
        toMonth: $('.to-month-datepicker'),
    };

    date.toMonth.attr('disabled', true);

    function initDatePickers($scope, fromDateChanged) {
        date.fromMonth.datepicker('destroy');
        date.toMonth.datepicker('destroy');
        var isCurrentYear = (parseInt($scope.query.year) === new Date().getFullYear());
        var fromDate = moment().month($scope.query.fromMonth).format('YYYY MM DD');

        var oldYearDate = moment().set('month', 11).format('YYYY MM DD');
        if (fromDateChanged) {
            date.toMonth.attr('disabled', false);
            $scope.query.toMonth = '';
        }
        date.fromMonth.datepicker({
            format: 'M',
            endDate: (isCurrentYear ? new Date() : new Date(oldYearDate)),
            minViewMode: 'months',
            maxViewMode: 'months',
        });

        date.toMonth.datepicker({
            format: 'M',
            endDate: (isCurrentYear ? new Date() : new Date(oldYearDate)),
            minViewMode: 'months',
            maxViewMode: 'months',
            startDate: new Date(fromDate)
        });
    }

</script>