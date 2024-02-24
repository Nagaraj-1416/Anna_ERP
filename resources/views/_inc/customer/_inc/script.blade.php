<script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
<script>
    app.controller('CustomerCreateController', function ($scope, $http) {
        // customer model
        $scope.customer = {
            'route_id' : null,
            'location_id' : null,
            'salutation' : null,
            'first_name' : null,
            'last_name' : null,
            'display_name' : null,
            'phone' : null,
            'fax' : null,
            'mobile' : null,
            'email' : null,
            'website' : null,
            'street_one' : null,
            'city' : null,
            'province' : null,
            'postal_code' : null,
            'country_id' : null,
        };

        //Error object
        $scope.errors = [];

        // Related elements
        $scope.el = {
            'dropdown' : $('.{{ (isset($dropdown) && $dropdown) ? $dropdown : 'cus-drop-down' }}'),
            'btn' : $('#{{ (isset($btn) && $btn) ? $btn : "cus-drop-down-add-btn" }}'),
            'sidebar' : $('#add-cus-sidebar'),
            'route' : $('.route-drop-down'),
            'routeLocation' : $('.location-drop-down'),
            'salutationDropDown' : $('.salutation-drop-down'),
            'countryDropDown' : $('.country-drop-down'),
            'loader' : $('.cus-create-preloader'),
        };

        // When click the add button open the model
        $scope.cusSlider = $scope.el.sidebar.slideReveal({
            trigger: $scope.el.btn,
            position: "right",
            width: '500px',
            push: false,
            overlay: true,
            shown: function(slider, trigger){
                // init scroll for side bar body
                $('#add-cus-body').slimScroll({
                    color: 'gray',
                    height: '100%',
                    railVisible: true,
                    alwaysVisible: false
                });
            },
            show: function(slider, trigger){
                $scope.hideLoader();
                $scope.resetForm();
            },
        });


        // close side bar
        $scope.closeSideBar = function(){
            $scope.cusSlider.slideReveal("toggle");
        };

        $scope.resetForm = function(){
            $scope.customer = {
                'route_id' : null,
                'location_id' : null,
                'salutation' : null,
                'first_name' : null,
                'last_name' : null,
                'display_name' : null,
                'phone' : null,
                'fax' : null,
                'mobile' : null,
                'email' : null,
                'website' : null,
                'street_one' : null,
                'city' : null,
                'province' : null,
                'postal_code' : null,
                'country_id' : null,
            };

            //Error object
            $scope.errors = [];
            if (!$scope.$$phase) $scope.$apply();
        };

        //save customer
        $scope.saveCustomer = function(){
            $scope.showLoader();
            $scope.storeCustomer();
        };

        // store customer
        $scope.coustomerStoreRoute = '{{ route('sales.customer.store') }}';
        $scope.storeCustomer = function()
        {
            $http.post($scope.coustomerStoreRoute, $scope.customer).then(function (response) {
                if (response.data){
                    $scope.setDropDownValue($scope.el.dropdown, response.data.id, response.data.display_name);
                }
                $scope.hideLoader();
                $scope.closeSideBar();
            }).catch(function (error) {
                if (error.hasOwnProperty('data') && error.data.hasOwnProperty('errors')){
                    $scope.errors = [];
                    $scope.mapErrors(error.data.errors);
                }
                $scope.hideLoader();
            });
        };

        // mapping errors
        $scope.mapErrors = function (errors) {
            $.map(errors, function (values, field) {
                if (values.hasOwnProperty('0')){
                    $scope.errors[field] = values[0];
                }
            });
        };

        // check has error
        $scope.hasError = function(name){
            if ($scope.errors.hasOwnProperty(name)) {
                if ($scope.errors[name]){
                    return true;
                }
            }
            return false;
        };

        // check has error
        $scope.getErrorMsg = function(name){
            if ($scope.errors.hasOwnProperty(name)) {
                return $scope.errors[name];
            }
            return '';
        };

        // show loader
        $scope.el.loader.addClass('hidden');
        $scope.showLoader = function(){
            $scope.el.loader.addClass('loading');
            $scope.el.loader.removeClass('hidden');
        };

        // hide loading
        $scope.hideLoader = function(){
            $scope.el.loader.removeClass('loading');
            $scope.el.loader.addClass('hidden');
        };

        /** set values to semantic UI drop-down */
        $scope.setDropDownValue = function (dd, value, name) {
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };

        // salutation dropdown
        $scope.el.salutationDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function(value){
                $scope.customer.salutation = value;
            }
        });

        // country dropdown
        $scope.el.countryDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function(value){
                $scope.customer.country_id = value;
            }
        });

        // route dropdown
        $scope.el.route.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function(value){
                $scope.customer.route_id = value;
                locationDropDown();
            }
        });

        // location dropdown
        function locationDropDown() {
            var routeId = $scope.el.route.dropdown('get value');
            var url = '{{ route('setting.route.location.search', ['routeId']) }}';
            url = url.replace('routeId', routeId);
            $scope.el.routeLocation.dropdown('setting', {
                apiSettings: {
                    url: url + '/{query}',
                    cache:false,
                },
                saveRemoteData:false,
                onChange: function(value){
                    $scope.customer.location_id = value;
                }
            });
        }
    });
</script>