<script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
<script>
    app.controller('HanoverController', function ($scope, $http) {
        // Related elements
        $scope.el = {
            btn: $('.handover-btn'),
            sidebar: $('#handover-sidebar'),
            loader: $('.cus-create-preloader'),
        };

        // When click the add button open the model
        $scope.cusSlider = $scope.el.sidebar.slideReveal({
            trigger: $scope.el.btn,
            position: "right",
            width: '800px',
            push: false,
            overlay: true,
            shown: function (slider, trigger) {
                // init scroll for side bar body
                $('#add-cus-body').slimScroll({
                    color: 'gray',
                    height: '100%',
                    railVisible: true,
                    alwaysVisible: false
                });
            },
            show: function (slider, trigger) {
                $scope.hideLoader();
                $scope.getHandoverData();
                if (!$scope.$$phase) $scope.$apply()
            }
        });
        // close side bar
        $scope.closeSideBar = function () {
            $scope.cusSlider.slideReveal("toggle");
        };

        // show loader
        $scope.el.loader.addClass('hidden');
        $scope.showLoader = function () {
            $scope.el.loader.addClass('loading');
            $scope.el.loader.removeClass('hidden');
        };

        // hide loading
        $scope.hideLoader = function () {
            $scope.el.loader.removeClass('loading');
            $scope.el.loader.addClass('hidden');
        };

        $scope.collection = [];
        $scope.errors = [];
        $scope.getHandOverRoute = '{{ route('cash.sales.get.handover') }}';
        $scope.getHandoverData = function () {
            $http.get($scope.getHandOverRoute).then(function (response) {
                if (response.data.error) {
                    $scope.errors = response.data;
                } else {
                    $scope.collection = response.data;
                }
            });
        };
        $scope.getHandoverData();
        $scope.submitUrl = '{{route('cash.sales.handover.save')}}';
        $scope.saveHandover = function () {
            $http.post($scope.submitUrl, []).then(function (response) {
                if (response.data.error) {
                    $scope.errors = response.data;
                } else {
                    window.location.reload();
                }
            })
        }
    });
</script>