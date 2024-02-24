<script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
<script>
    app.controller('ExpenseTypeController', function ($scope, $http) {
        // expenseCategory model
        $scope.expenseType = {
            name : null,
            description : null,
            account_id : null,
            is_mobile_enabled : null
        };

        //Error object
        $scope.errors = [];

        // Related elements
        $scope.el = {
            'dropdown': $('.{{ (isset($dropdown) && $dropdown) ? $dropdown : 'expense-type-drop-down' }}'),
            'accountDropdown': $('.account-drop-down'),
            'btn': $('#{{ (isset($btn) && $btn) ? $btn : "expense-type-drop-down-add-btn" }}'),
            'sidebar': $('#add-expense-type-sidebar'),
            'loader': $('.expense-type-create-preloader'),
        };

        $scope.urls = {
            account : '{{ route('finance.expense.account.search') }}'
        };

        // When click the add button open the model
        $scope.expenseTypeSlider = $scope.el.sidebar.slideReveal({
            trigger: $scope.el.btn,
            position: "right",
            width: '400px',
            push: false,
            overlay: true,
            shown: function (slider, trigger) {
                // init scroll for side bar body
                $('#add-expense-type-body').slimScroll({
                    color: 'gray',
                    height: '100%',
                    railVisible: true,
                    alwaysVisible: false
                });
            },
            show: function (slider, trigger) {
                $scope.hideLoader();
                $scope.resetForm();
            },
        });


        // close side bar
        $scope.closeSideBar = function () {
            $scope.expenseTypeSlider.slideReveal("toggle");
        };

        $scope.resetForm = function () {
            $scope.expenseType = {
                name : null,
                description : null,
                account_id : null,
                is_mobile_enabled : null
            };

            //Error object
            $scope.errors = [];
            if (!$scope.$$phase) $scope.$apply();
        };

        //save expenseCategory
        $scope.saveExpenseType = function () {
            $scope.showLoader();
            $scope.storeExpenseType();
        };

        // store expenseCategory
        $scope.expenseTypeStoreRoute = '{{ route('expense.type.store') }}';
        $scope.storeExpenseType = function () {
            $http.post($scope.expenseTypeStoreRoute, $scope.expenseType).then(function (response) {
                if (response.data) {
                    $scope.setDropDownValue($scope.el.dropdown, response.data.id, response.data.name);
                }
                $scope.hideLoader();
                $scope.closeSideBar();
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

        /** set values to semantic UI drop-down */
        $scope.setDropDownValue = function (dd, value, name) {
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };

        /** account dropdown init */
        $scope.el.accountDropdown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: $scope.urls.account + '/{query}',
                cache: false
            },
            onChange : function (value) {
                $scope.expenseType.account_id = value;
                console.log($scope.expenseType)
            }
        });

    });
</script>