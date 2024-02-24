@section('script')
    @parent
    <script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        app.controller('OpeningController', function ($scope, $http) {
            // designation model
            $scope.opening = {
                'opening': '',
                'opening_at': '',
                'balance_type': '',
                'references': []
            };

            $scope.references = {
                reference_no: null,
                bill_no: null,
                bill_date: null,
                bill_amount: null,
                bill_due: null,
                bill_due_age: null,
            };
            //Error object
            $scope.errors = [];
            $scope.supplierId = null;
            // Related elements
            $scope.el = {
                'btn': $('.opening-button'),
                'sidebar': $('#add-opening-sidebar'),
                'loader': $('.designation-create-preloader'),
            };

            // When click the add button open the model
            $scope.designationSlider = $scope.el.sidebar.slideReveal({
                trigger: $scope.el.btn,
                position: "right",
                width: '1400px',
                push: false,
                overlay: true,
                shown: function (slider, trigger) {
                    // init scroll for side bar body
                    $('#add-designation-body').slimScroll({
                        color: 'gray',
                        height: '100%',
                        railVisible: true,
                        alwaysVisible: false
                    });
                },
                show: function (slider, trigger) {
                    $scope.hideLoader();
                    $scope.resetForm();
                }
            });

            $scope.el.btn.click(function () {
                $scope.supplierId = $(this).data('id');
            });
            // close side bar
            $scope.closeSideBar = function () {
                $scope.designationSlider.slideReveal("toggle");
            };

            $scope.resetForm = function () {
                $scope.references = {
                    reference_no: null,
                    bill_no: null,
                    bill_date: null,
                    bill_amount: null,
                    bill_due: null,
                    bill_due_age: null,
                };

                //Error object
                $scope.errors = [];
                if (!$scope.$$phase) $scope.$apply();
            };

            //save designation
            $scope.saveOpening = function () {
                $scope.showLoader();
                $scope.storeOpening();
            };

            // store designation
            $scope.openingStoreRoute = '{{ route('purchase.supplier.opening.store', ['supplier' => 'SUPPLIER']) }}';
            // $scope.designationStoreRoute = '/';
            $scope.storeOpening = function () {
                $http.post($scope.openingStoreRoute.replace('SUPPLIER', $scope.supplierId), $scope.opening).then(function (response) {
                    $scope.hideLoader();
                    $scope.closeSideBar();
                    swal('Success', 'Opening balance successfully updated', 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1000)
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

            $scope.addReference = function () {
                $scope.opening.references.push(angular.copy($scope.references));
            };
            $scope.addReference();
            $scope.removeReference = function (key) {
                $scope.opening.references = $scope.removeByKey($scope.opening.references, key);
            };

            /** remove item from object by key */
            $scope.removeByKey = function (array, index) {
                if (array.hasOwnProperty(index)) {
                    array.splice(index, 1);
                }
                return array;
            };
        }).directive('referenceLoop', function () {
            return function (scope, element, attrs) {
                var date = $(".datepicker");
                date.datepicker({
                    'autoclose': true,
                    'format' : "yyyy-mm-dd",
                    'endDate' : 'yesterday',
                });
            }
        });
    </script>
@endsection