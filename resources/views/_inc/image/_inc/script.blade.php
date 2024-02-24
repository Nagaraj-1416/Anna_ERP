<script>
    function initSide($scope, $http) {
        // expenseCategory model
        $scope.image = {
            image: null,
        };

        $scope.errors = [];

        // close side bar
        $scope.closeSideBar = function () {
            $scope.imageSlider.slideReveal("toggle");
        };

        $scope.resetForm = function () {
            $scope.image = {
                image: null,
            };

            //Error object
            $scope.errors = [];
            if (!$scope.$$phase) $scope.$apply();
        };

        //save expenseCategory
        $scope.saveImage = function () {
            $scope.showLoader();
            $scope.storeImage();
        };

        // store expenseCategory
        $scope.imageStoreRoute = '{{ $url }}';
        $scope.storeImage = function () {
            let image = document.getElementById("image");
            let newForm = new FormData();
            newForm.append('image', image.files[0]);
            var config = {
                transformRequest: $scope.identity,
                headers: {'Content-Type': undefined}
            };
            $http.post($scope.imageStoreRoute.replace('ID', $scope.selectedProduct.id), newForm, config).then(function (response) {
                $scope.hideLoader();
                $scope.closeSideBar();
                window.location.reload();
            }).catch(function (error) {
                if (error.hasOwnProperty('data') && error.data.hasOwnProperty('errors')) {
                    $scope.errors = [];
                    $scope.mapErrors(error.data.errors);
                }
                $scope.hideLoader();
            });
        };

        // mapping errors
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
    }
</script>