<script>
    function dateRangeDropDown($scope) {
        $scope.daterangeDD = $('.date-range');
        $scope.daterangeValue = '';
        $scope.handelChange = function (val, text) {
            $scope.daterangeValue = val;
            $scope.query.fromDate = '';
            $scope.query.toDate = '';
            switch (val) {
                case 'today':
                    $scope.query.fromDate = '{{ carbon()->toDateString() }}';
                    $scope.query.toDate = '{{ carbon()->toDateString() }}';
                    break;
                case 'this week':
                    $scope.query.fromDate = '{{ carbon()->startOfWeek()->toDateString() }}';
                    $scope.query.toDate = '{{ carbon()->endOfWeek()->toDateString() }}';
                    break;
                case 'this month':
                    $scope.query.fromDate = '{{ carbon()->startOfMonth()->toDateString() }}';
                    $scope.query.toDate = '{{ carbon()->endOfMonth()->toDateString() }}';
                    break;
                case 'this year':
                    $scope.query.fromDate = '{{ carbon()->startOfYear()->toDateString() }}';
                    $scope.query.toDate = '{{ carbon()->endOfYear()->toDateString() }}';
                    break;
                case 'yesterday':
                    $scope.query.fromDate = '{{ carbon()->subDay()->toDateString() }}';
                    $scope.query.toDate = '{{ carbon()->subDay()->toDateString() }}';
                    break;
                case 'previous week':
                    $scope.query.fromDate = '{{ carbon()->subWeek()->startOfWeek()->toDateString() }}';
                    $scope.query.toDate = '{{ carbon()->subWeek()->endOfWeek()->toDateString() }}';
                    break;
                case 'previous month':
                    $scope.query.fromDate = '{{ carbon()->subMonth()->startOfMonth()->toDateString() }}';
                    $scope.query.toDate = '{{ carbon()->subMonth()->endOfMonth()->toDateString() }}';
                    break;
                case 'previous year':
                    $scope.query.fromDate = '{{ carbon()->subYear()->startOfYear()->toDateString() }}';
                    $scope.query.toDate = '{{ carbon()->subYear()->endOfYear()->toDateString() }}';
                    break;
            }

            // $scope.fromDate = $scope.query.fromDate;
            // $scope.toDate = $scope.query.toDate;
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