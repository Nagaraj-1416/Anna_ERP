<script src="{{ asset('js/vendor/slidereveal.js') }}"></script>
<script>
    app.controller('BrandCreateController', function ($scope, $http) {
        // customer model
        $scope.brand = {
            'name' : null,
            'description' : null,
        };
        $scope.index = null;
        //Error object
        $scope.errors = [];

        // Related elements
        $scope.el = {
            'sidebar' : $('#add-brand-sidebar'),
            'btn' : '.brand-drop-down-add-btn',
            'loader' : $('.brand-create-preloader'),
            'dropdown' : '.brand-drop-down',
        };

        // When click the add button open the model
        $scope.brandSlider = $scope.el.sidebar.slideReveal({
            position: "right",
            width: '400px',
            push: false,
            overlay: true,
            shown: function(slider, trigger){
                // init scroll for side bar body
                $('#add-brand-body').slimScroll({
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
            hide: function(slider, trigger){
                $scope.index = null;
            }
        });

        $('body').on('click', $scope.el.btn, function() {
            $scope.index = $(this).data('index');
            $scope.brandSlider.slideReveal("toggle");
        });

        // close side bar
        $scope.closeSideBar = function(){
            $scope.brandSlider.slideReveal("toggle");
            $scope.index = null;
        };

        $scope.resetForm = function(){
            $scope.brand = {
                'name' : null,
                'description' : null,
            };

            //Error object
            $scope.errors = [];
            if (!$scope.$$phase) $scope.$apply();
        };

        //save customer
        $scope.saveBrand = function(){
            $scope.showLoader();
            $scope.storeBrand();
        };

        // store customer
        $scope.brandStoreRoute = '{{ route('setting.brand.store') }}';
        $scope.storeBrand = function()
        {
            $http.post($scope.brandStoreRoute, $scope.brand).then(function (response) {
                if (response.data){
                    var $dropdown = $( $scope.el.dropdown + '[data-index="' + $scope.index + '"]');
                    $scope.setDropDownValue($dropdown, response.data.id, response.data.name);
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
    });
</script>