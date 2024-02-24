@section('script')
    @parent
    <script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        app.controller('OpeningController', function ($scope, $http) {
            $scope.productTotal = 0;
            $scope.refEditMode = false;
            $scope.refEditingIndex = null;
            /** require urls */
            $scope.urls = {
                product: '{{ route('setting.product.search', ['type' => 'Finished Good']) }}',
                customerShow: '{{ route('sales.customer.show', $customer->id) }}'
            };

            // designation model
            $scope.opening = {
                opening: 0,
                opening_at: '',
                balance_type: '',
                references: []
            };

            @if(isset($openingData))
                $scope.opening = @json($openingData);
            @endif

                $scope.product = {
                product_id: 0,
                product_name: '',
                quantity: 0,
                rate: 0,
                total: 0
            };

            $scope.reference = {
                reference_no: null,
                invoice_no: null,
                invoice_date: null,
                invoice_amount: null,
                invoice_due: 0,
                invoice_due_age: null,
                products: []
            };

            $scope.referenceItem = {};

            $scope.referenceItemErrors = {
                reference_no: null,
                invoice_no: null,
                invoice_date: null,
                invoice_amount: null,
                invoice_due: null,
                invoice_due_age: null,
                products: []
            };
            $scope.openingErrors = {};
            $scope.productError = {
                product_id: null,
                quantity: null,
                rate: null,
            };

            $scope.productDeletable = true;

            //Error object
            $scope.errors = [];
            $scope.customerId = null;
            // Related elements

            $scope.el = {
                btn: $('.opening-button'),
                sidebar: $('#add-opening-sidebar'),
                loader: $('.designation-create-preloader'),
                submitBtn: $('.opining-submit'),
                form: $('.opining-form')
            };

            // When click the add button open the model
            $scope.designationSlider = $scope.el.sidebar.slideReveal({
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
                $scope.customerId = $(this).data('id');
            });

            // close side bar
            $scope.toggleSideBar = function () {
                $scope.resetForm();
                $scope.designationSlider.slideReveal("toggle");
                $scope.hideLoader();
                $scope.referenceItem = angular.copy($scope.reference);
                $scope.openingErrors = {};
                $scope.referenceItemErrors = {
                    invoice_no: null,
                    invoice_date: null,
                    invoice_amount: null,
                    invoice_due: null,
                    invoice_due_age: null,
                    products: []
                };
            };

            $scope.resetForm = function () {
                $scope.reference = {
                    reference_no: null,
                    invoice_no: null,
                    invoice_date: null,
                    invoice_amount: null,
                    invoice_due: 0,
                    invoice_due_age: null,
                    products: []
                };
                //Error object
                $scope.errors = [];
                if (!$scope.$$phase) $scope.$apply();
            };

            //save designation
            $scope.saveOpening = function () {
                $scope.showLoader();
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

            $scope.addProduct = function (index) {
                $scope.referenceItem.products.push(angular.copy($scope.product));
                if ($scope.referenceItem.products.length > 1) {
                    $scope.productDeletable = true;
                } else {
                    $scope.productDeletable = true;
                }
            };

            $scope.removeProduct = function (key) {
                $scope.referenceItem.products = $scope.removeByKey($scope.referenceItem.products, key);
                if ($scope.referenceItem.products.length > 1) {
                    $scope.productDeletable = true;
                } else {
                    $scope.productDeletable = true;
                }
                $scope.calculateAmount();
            };

            $scope.addReference = function () {
                $scope.toggleSideBar();
                $scope.addProduct();
            };

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

            $scope.invoiceDateChange = function () {
                if ($scope.referenceItem) {
                    var item = $scope.referenceItem;
                    var invoiceDate = moment(item.invoice_date);
                    var now = moment(new Date());
                    var duration = moment.duration(now.diff(invoiceDate));
                    var days = duration.asDays();
                    days = Math.round(days);
                    days = days + ' days';
                    $scope.referenceItem.invoice_due_age = days;
                }
            };

            /** init product drop down */
            $scope.initProductDropDown = function (el) {
                el.dropdown('setting', {
                    forceSelection: false,
                    saveRemoteData: false,
                    apiSettings: {
                        url: $scope.urls.product + '/{query}',
                        cache: false
                    },
                    onChange: function (value, name, el) {
                        var index = $(this).data('index').toString();
                        if (!$scope.referenceItem.products.hasOwnProperty(index)) {
                            $scope.referenceItem.products[index] = angular.copy($scope.product);
                        }
                        if (value) {
                            $scope.referenceItem.products[index].product_id = value;
                        }
                        if (name) {
                            $scope.referenceItem.products[index].product_name = name;
                        }

                    }
                });
            };

            $scope.calculateAmount = function (refIndex) {
                $scope.referenceItem.invoice_amount = 0;
                $.each($scope.referenceItem.products, function (index, product) {
                    $scope.referenceItem.products[index].total = product.quantity * product.rate;
                    $scope.referenceItem.invoice_amount += $scope.referenceItem.products[index].total;
                });
            };

            $scope.changedQuantity = function (index, refIndex) {
                $scope.calculateAmount(refIndex);
            };

            $scope.changedRate = function (index, refIndex) {
                $scope.calculateAmount(refIndex);
            };

            $scope.validateRef = function () {
                var valid = true;
                $scope.referenceItemErrors.invoice_no = null;
                if (!$scope.referenceItem.invoice_no) {
                    $scope.referenceItemErrors.invoice_no = 'The invoice no is invalid';
                    valid = false;
                }

                $scope.referenceItemErrors.invoice_amount = null;
                if (!$scope.referenceItem.invoice_amount) {
                    $scope.referenceItemErrors.invoice_amount = 'The invoice amount is invalid';
                    valid = false;
                }

                $scope.referenceItemErrors.invoice_date = null;
                if (!$scope.referenceItem.invoice_date) {
                    $scope.referenceItemErrors.invoice_date = 'The invoice date is invalid';
                    valid = false;
                }

                $scope.referenceItemErrors.invoice_due = null;
                // if (!$scope.referenceItem.invoice_due || $scope.referenceItem.invoice_amount < $scope.referenceItem.invoice_due) {
                //     $scope.referenceItemErrors.invoice_due = 'The invoice due is invalid';
                //     valid = false;
                // }

                $.each($scope.referenceItem.products, function (index, product) {
                    var error = angular.copy($scope.productError);
                    if (!product.product_id) {
                        // error.product_id = 'The product is invalid';
                        // valid = false;
                    }

                    if (product.product_id && (!product.quantity || product.quantity < 1 || isNaN(product.quantity))) {
                        error.quantity = 'The quantity is invalid';
                        valid = false;
                    }
                    if (product.product_id && (!product.rate || product.rate < 1 || isNaN(product.rate))) {
                        error.rate = 'The rate is invalid';
                        valid = false;
                    }

                    $scope.referenceItemErrors.products[index] = error;
                });
                return valid;
            };

            $scope.closeSideBar = function () {
                $scope.toggleSideBar();
                $scope.refEditMode = false;
                $scope.refEditingIndex = null;
            };

            $scope.saveReference = function () {
                var valid = $scope.validateRef();
                if (valid) {
                    if ($scope.refEditMode) {
                        $scope.opening.opening = 0;
                        $.each($scope.opening.references, function (index, ref) {
                            $scope.opening.opening += ref.invoice_due;
                        });
                        $scope.opening.references[$scope.refEditingIndex] = $scope.referenceItem;
                    } else {
                        $scope.opening.opening += $scope.referenceItem.invoice_due;
                        $scope.opening.references.push(angular.copy($scope.referenceItem));
                    }
                    $scope.closeSideBar();
                }
            };

            $scope.getProductError = function (index, filed) {
                if ($scope.referenceItemErrors.products.hasOwnProperty(index) && $scope.referenceItemErrors.products[index].hasOwnProperty(filed)) {
                    return $scope.referenceItemErrors.products[index][filed];
                }
                return null;
            };

            $scope.getRefError = function (filed) {
                if ($scope.referenceItemErrors.hasOwnProperty(filed)) {
                    return $scope.referenceItemErrors[filed];
                }
                return null;
            };

            $scope.setDropDownValue = function (dd, value, name) {
                dd.dropdown("refresh");
                dd.dropdown('set value', value);
                dd.dropdown('set text', name);
            };

            $scope.setProductItemsDropDownValues = function () {
                $.each($scope.referenceItem.products, function (index, product) {
                    $scope.setDropDownValue($('.product-drop-down[data-index="' + index + '"]'), product.product_id, product.product_name);
                });
            };

            $scope.editReference = function (index) {
                if ($scope.opening.references && $scope.opening.references.hasOwnProperty(index)) {
                    $scope.refEditingIndex = index;
                    $scope.toggleSideBar();
                    $scope.referenceItem = $scope.opening.references[index];
                    $scope.refEditMode = true;
                }
            };

            $scope.el.submitBtn.click(function (e) {
                e.preventDefault();
                $(this).prop('disabled', true);
                var url = $scope.el.form.attr('action');
                var formMethod = 'POST';
                var method = $scope.el.form.find('[name="_method"]').val();
                if (method === 'PATCH') {
                    formMethod = 'PATCH';
                }
                var msg = "Opening balance " + (formMethod === 'PATCH' ? 'updated' : 'added') + "!";
                $http({method: formMethod, url: url, data: $scope.opening}).then(function (response) {
                    swal("Success!", msg, "success");
                    setTimeout(function () {
                        window.location = $scope.urls.customerShow;
                    }, 300);
                }).catch(function (errors) {
                    if (errors.hasOwnProperty('data') && errors.data.hasOwnProperty('errors')) {
                        $.each(errors.data.errors, function (filed, error) {
                            $scope.openingErrors[filed] = error[0];
                        });
                    }
                    $scope.el.submitBtn.removeAttr('disabled');
                });
            });

            $scope.getErrorMsg = function (feild) {
                if ($scope.openingErrors.hasOwnProperty(feild)) {
                    return $scope.openingErrors[feild];
                }
                return null;
            }

        }).directive('referenceLoop', function () {
            return function (scope, element, attrs) {
                var date = $(".datepicker");
                date.datepicker({
                    'autoclose': true,
                    'format': "yyyy-mm-dd",
                    'endDate': 'yesterday',
                });
            }
        }).directive('productLoop', function () {
            return function (scope, element, attrs) {
                scope.initProductDropDown($(".product-drop-down"));
                if (scope.$last) {
                    if (scope.refEditMode) {
                        setTimeout(scope.setProductItemsDropDownValues, 300)
                    }
                }
            }
        });
    </script>
@endsection
