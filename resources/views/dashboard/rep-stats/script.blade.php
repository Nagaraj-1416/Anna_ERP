<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
@include('report.general.date-range-script')
<script>
    app.controller('RepStatsController', ['$scope', '$http', function ($scope, $http) {

        $scope.query = {
            fromDate: '',
            toDate: ''
        };

        $scope.loading = true;

        $scope.dropdown = {
            route: $('.route-drop-down')
        };

        // route drop-down
        $scope.dropdown.route.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val, name) {
                $scope.query.route = val;
                $scope.routeName = name;
            }
        });

        $scope.visits = [];
        $scope.daterangeDD = $('.date-range');
        $scope.daterangeValue = '';

        // initiate date range drop-down
        dateRangeDropDown($scope);

        $scope.length = 0;

        // generate report using filters
        $scope.generate = function () {
            $scope.fromDate = $scope.query.fromDate;
            $scope.toDate = $scope.query.toDate;
            $scope.loading = true;
            var reportRoute = '{{ route('rep.stats') }}';
            $http.get(reportRoute + '?' + $.param($scope.query)).then(function (response) {
                $scope.allocations = response.data.allocations;
                $scope.visits = response.data.customers;
                $scope.totalAllocated = response.data.totalAllocated;
                $scope.totalVisited = response.data.totalVisited;
                $scope.totalNotVisited = response.data.totalNotVisited;
                $scope.loading = false;
                $scope.length = _.toArray($scope.visits).length;
            })
        };

        $scope.generate();

        // reset filters
        $scope.resetFilters = function () {
            $scope.query = {
                fromDate: '',
                toDate: ''
            };
            $scope.dropdown.route.dropdown('clear');
            $scope.daterangeDD.dropdown('clear');
            $scope.daterangeDD.dropdown('set text', 'Today').dropdown('set value', 'today');
            $scope.generate();
        };

        $scope.sum = function (array) {
            var sum = _.reduce(array, function (memo, num) {
                return memo + num;
            }, 0);
            return sum;
        };

    }]);
</script>