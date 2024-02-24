<div ng-controller="DesignationController">
    <div id="add-designation-sidebar" class="card card-outline-info disabled-dev">
        <div class="designation-create-preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
            </svg>
        </div>
        <div class="card-header ">
            <h3 class="m-b-0 text-white">Designation</h3>
            <h6 class="card-subtitle text-white">Create new designation and associate</h6>
        </div>
        <div class="card-body" id="add-designation-body">
            <div class="form">
                <div class="form-body">
                    <div class="row">
                        {{--<div class="col-md-12">
                            <h4 class="box-title">Designation Details</h4>
                            <hr>
                        </div>--}}
                        <div class="col-md-12">
                            <div class="form-group required" ng-class="hasError('name') ? 'has-danger' : ''">
                                <label for="name" class="control-label form-control-label">Name</label>
                                <input ng-model="designation.name" class="form-control"
                                       placeholder="enter designation name" name="name" type="text" id="name">
                                <p class="form-control-feedback">@{{ getErrorMsg('name') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" ng-class="hasError('notes') ? 'has-danger' : ''">
                                <label for="notes" class="control-label form-control-label">Notes</label>
                                <textarea name="notes" placeholder="enter designation related notes..."
                                          ng-model="designation.notes" id="designationNotes" cols="30" rows="6"
                                          class="form-control"></textarea>
                                <p class="form-control-feedback">@{{ getErrorMsg('notes') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <button type="button"
                                    class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar"
                                    data-ng-click="saveExpenseCategory($event)">
                                <i class="fa fa-check"></i>
                                Submit
                            </button>
                            <button type="button" class="btn btn-inverse waves-effect waves-light"
                                    data-ng-click="closeSideBar($event)">
                                <i class="fa fa-remove"></i> Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
    @parent
    <script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
    <script>
        app.controller('DesignationController', function ($scope, $http) {
            // designation model
            $scope.designation = {
                'name': '',
                'notes': ''
            };

            //Error object
            $scope.errors = [];
            $scope.dropdown = $('.designation-drop-down');
            $scope.btn = $('#designation-drop-down-add-btn');

            // Related elements
            $scope.el = {
                'dropdown': $scope.dropdown,
                'btn': $scope.btn,
                'sidebar': $('#add-designation-sidebar'),
                'loader': $('.designation-create-preloader'),
            };

            // When click the add button open the model
            $scope.designationSlider = $scope.el.sidebar.slideReveal({
                trigger: $scope.el.btn,
                position: "right",
                width: '400px',
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
                },
            });


            // close side bar
            $scope.closeSideBar = function () {
                $scope.designationSlider.slideReveal("toggle");
            };

            $scope.resetForm = function () {
                $scope.designation = {
                    'name': '',
                    'notes': ''
                };

                //Error object
                $scope.errors = [];
                if (!$scope.$$phase) $scope.$apply();
            };

            //save designation
            $scope.saveExpenseCategory = function () {
                $scope.showLoader();
                $scope.storeExpenseCategory();
            };

            // store designation
            $scope.designationStoreRoute = '{{ route('setting.designation.store') }}';
            // $scope.designationStoreRoute = '/';
            $scope.storeExpenseCategory = function () {
                $http.post($scope.designationStoreRoute, $scope.designation).then(function (response) {
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

        });
    </script>
@endsection