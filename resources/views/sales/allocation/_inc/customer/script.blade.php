<script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
<script>
    app.controller('AddCustomerController', function ($scope, $http) {
        $scope.addCustomer = {
            customer: null,
        };

        $scope.errors = [];

        $scope.el = {
            btn: $('.customers-sidebar-btn'),
            sidebar: $('#customers-sidebar'),
            loader: $('.cus-create-preloader'),
            customerDD: $('.customers-dropdown'),
        };

        $scope.el.customerDD.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (val) {
                $scope.addCustomer.customer = val;
            }
        });

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
                $scope.resetForm();
                if (!$scope.$$phase) $scope.$apply()
            }
        });

        $scope.closeSideBar = function () {
            $scope.cusSlider.slideReveal("toggle");
        };

        $scope.resetForm = function () {
            $scope.addCustomer = {
                customer: '',
            };
            $scope.el.customerDD.dropdown('clear');
            //Error object
            $scope.errors = [];
            if (!$scope.$$phase) $scope.$apply();
        };

        $scope.hideLoader = function () {
            $scope.el.loader.removeClass('loading');
            $scope.el.loader.addClass('hidden');
        };
        $scope.addCustomerRoute = '{{ route('sales.allocation.add.customer', [$allocation])}}';
        $scope.submitForm = function () {
            $scope.showLoader();
            $http.post($scope.addCustomerRoute, $scope.addCustomer).then(function (response) {
                if (response.data) {
                    window.location.reload();
                }
                $scope.hideLoader();
                $scope.closeSideBar();
            }).catch(function (error) {
                if (error.hasOwnProperty('data') && error.data.hasOwnProperty('message') && error.data.message === 'This action is unauthorized.') {
                    $scope.errors = [];
                    $scope.errors['unauthorized'] = true;
                }
                if (error.hasOwnProperty('data') && error.data.hasOwnProperty('errors')) {
                    $scope.errors = [];
                    $scope.mapErrors(error.data.errors);
                }
                $scope.hideLoader();
            });
        };

        // show loader
        $scope.el.loader.addClass('hidden');
        $scope.showLoader = function () {
            $scope.el.loader.addClass('loading');
            $scope.el.loader.removeClass('hidden');
        };

        $scope.mapErrors = function (errors) {
            $.map(errors, function (values, field) {
                if (values.hasOwnProperty('0')) {
                    $scope.errors[field] = values[0];
                }
            });
        };

        // check has error
        $scope.hasError = function (name) {
            if ($scope.errors.hasOwnProperty(name)) {
                if ($scope.errors[name]) {
                    return true;
                }
            }
            return false;
        };

        // check has error
        $scope.getErrorMsg = function (name) {
            if ($scope.errors.hasOwnProperty(name)) {
                return $scope.errors[name];
            }
            return '';
        };
    });
</script>