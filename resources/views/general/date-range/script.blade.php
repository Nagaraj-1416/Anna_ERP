<script>
    function dateRangeDropDown($scope) {
        $scope.daterangeDD = $('.date-range');
        $scope.daterangeValue = '';
        $scope.daterangeDD.dropdown('clear');
        $scope.handelChange = function (val, text) {
            $scope.daterangeValue = val;
            $scope.query.from_date = '';
            $scope.query.to_date = '';
            switch (val) {
                case 'today':
                    $scope.query.from_date = '{{ carbon()->toDateString() }}';
                    $scope.query.to_date = '{{ carbon()->toDateString() }}';
                    break;
                case 'this week':
                    $scope.query.from_date = '{{ carbon()->startOfWeek()->toDateString() }}';
                    $scope.query.to_date = '{{ carbon()->endOfWeek()->toDateString() }}';
                    break;
                case 'this month':
                    $scope.query.from_date = '{{ carbon()->startOfMonth()->toDateString() }}';
                    $scope.query.to_date = '{{ carbon()->endOfMonth()->toDateString() }}';
                    break;
                case 'this year':
                    $scope.query.from_date = '{{ carbon()->startOfYear()->toDateString() }}';
                    $scope.query.to_date = '{{ carbon()->endOfYear()->toDateString() }}';
                    break;
                case 'yesterday':
                    $scope.query.from_date = '{{ carbon()->subDay()->toDateString() }}';
                    $scope.query.to_date = '{{ carbon()->subDay()->toDateString() }}';
                    break;
                case 'previous week':
                    $scope.query.from_date = '{{ carbon()->subWeek()->startOfWeek()->toDateString() }}';
                    $scope.query.to_date = '{{ carbon()->subWeek()->endOfWeek()->toDateString() }}';
                    break;
                case 'previous month':
                    $scope.query.from_date = '{{ carbon()->subMonth()->startOfMonth()->toDateString() }}';
                    $scope.query.to_date = '{{ carbon()->subMonth()->endOfMonth()->toDateString() }}';
                    break;
                case 'previous year':
                    $scope.query.from_date = '{{ carbon()->subYear()->startOfYear()->toDateString() }}';
                    $scope.query.to_date = '{{ carbon()->subYear()->endOfYear()->toDateString() }}';
                    break;
            }
            if (!$scope.$$phase) $scope.$apply();
            $scope.handleDateRangeChange(val);
        };
        $scope.daterangeDD.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: $scope.handelChange
        });
    }
</script>