<script>

    function dateRangeDropDown($scope) {
        $scope.daterangeDD = $('.date-range');
        $scope.daterangeValue = '';
        $scope.handelChange = function (val, text) {
            $scope.daterangeValue = val;
            $scope.query.date = '';
            switch (val) {
                case 'today':
                    $scope.query.date = '{{ carbon()->toDateString() }}';
                    break;
                case 'this week':
                    $scope.query.date = '{{ carbon()->endOfWeek()->toDateString() }}';
                    break;
                case 'this month':
                    $scope.query.date = '{{ carbon()->endOfMonth()->toDateString() }}';
                    break;
                case 'this year':
                    $scope.query.date = '{{ carbon()->endOfYear()->toDateString() }}';
                    break;
                case 'yesterday':
                    $scope.query.date = '{{ carbon()->startOfDay()->subDay()->toDateString() }}';
                    break;
                case 'previous week':
                    $scope.query.date = '{{ carbon()->subWeek()->endOfWeek()->toDateString() }}';
                    break;
                case 'previous month':
                    $scope.query.date = '{{ carbon()->subMonth()->endOfMonth()->toDateString() }}';
                    break;
                case 'previous year':
                    $scope.query.date = '{{ carbon()->subYear()->endOfYear()->toDateString() }}';
                    break;
            }

            $scope.date = $scope.query.date;
            if (!$scope.$$phase) $scope.$apply();
        };
        $scope.daterangeDD.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: $scope.handelChange
        });

        $scope.daterangeDD.dropdown('set text', 'Today').dropdown('set value', 'today');
    }

</script>