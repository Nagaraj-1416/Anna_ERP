<script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
<script>
    app.controller('LoginAsControllerController', function ($scope, $http) {
        // refund model
        $scope.loginAs = {
            user_id: null,
        };
        //Error object
        $scope.errors = [];

        // Related elements
        $scope.el = {
            btn: $('.login-as-sidebar-btn'),
            sidebar: $('#login-as-sidebar'),
            loader: $('.cus-create-preloader'),
            userDD: $('.user-dropdown'),
        };
        $scope.el.userDD.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: '{{ route('setting.user.login.as.search') }}' + '/{query}',
                cache: false
            },
            onChange: function (val) {
                $scope.loginAs.user_id = val;
            }
        });
        // When click the add button open the model
        $scope.cusSlider = $scope.el.sidebar.slideReveal({
            trigger: $scope.el.btn,
            position: "right",
            width: '400px',
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
        // close side bar
        $scope.closeSideBar = function () {
            $scope.cusSlider.slideReveal("toggle");
        };

        $scope.resetForm = function () {
            $scope.loginAs = {
                user_id: '',
            };
            $scope.el.userDD.dropdown('clear');
            //Error object
            $scope.errors = [];
            if (!$scope.$$phase) $scope.$apply();
        };

        //save loginAs
        $scope.loginAsRoute = '{{ route('setting.user.login.as') }}';
        $scope.LoginAsPost = function () {
            $scope.showLoader();
            $http.post($scope.loginAsRoute, $scope.loginAs).then(function (response) {
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
        $scope.clearRelativeFields = function () {
            if (!$scope.edit) {
                $scope.loginAs.user_id = '';
            }
        };
        // hide loading
        $scope.hideLoader = function () {
            $scope.el.loader.removeClass('loading');
            $scope.el.loader.addClass('hidden');
        };

        /** set values to semantic UI drop-down */
        $scope.setDropDownValue = function (dd, value, name) {
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };
    });
</script>