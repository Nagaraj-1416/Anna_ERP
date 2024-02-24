<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group {{ $errors->has('company_id') ? 'has-danger' : '' }}">
                <label class="control-label">Company</label>
                <div class="ui fluid  search selection dropdown company-drop-down {{ $errors->has('company_id') ? 'error' : '' }}">
                    @if(isset($dailyStock))
                        <input name="company_id" type="hidden" value="{{ old('_token') ? old('company_id'): $dailyStock->company_id }}">
                    @else
                        <input name="company_id" type="hidden" value="{{ old('_token') ? old('company_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a company</div>
                    <div class="menu">
                        @foreach(companyDropDown() as $key => $company)
                            <div class="item" data-value="{{ $key }}">{{ $company }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('company_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{ $errors->has('store_id') ? 'has-danger' : '' }}">
                <label class="control-label">Store</label>
                <div class="ui fluid  search selection dropdown store-drop-down {{ $errors->has('store_id') ? 'error' : '' }}">
                    @if(isset($dailyStock))
                        <input name="store_id" type="hidden" value="{{ old('_token') ? old('store_id'): $dailyStock->store_id }}">
                    @else
                        <input name="store_id" type="hidden" value="{{ old('_token') ? old('store_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a store</div>
                    <div class="menu">
                        @foreach(storeDropDown() as $key => $store)
                            <div class="item" data-value="{{ $key }}">{{ $store }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('store_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{ $errors->has('sales_location_id') ? 'has-danger' : '' }}">
                <label class="control-label">Shop</label>
                <div class="ui fluid  search selection dropdown shop-drop-down {{ $errors->has('sales_location_id') ? 'error' : '' }}">
                    @if(isset($dailyStock))
                        <input name="sales_location_id" type="hidden" value="{{ old('_token') ? old('sales_location_id'): $dailyStock->sales_location_id }}">
                    @else
                        <input name="sales_location_id" type="hidden" value="{{ old('_token') ? old('sales_location_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a shop</div>
                    <div class="menu">
                        @foreach(shopDropDown() as $key => $shop)
                            <div class="item" data-value="{{ $key }}">{{ $shop }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('sales_location_id') }}</p>
            </div>
        </div>
    </div>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        app.controller('DailyStockController', ['$scope', '$http', function ($scope, $http) {

            $scope.dropdown = {
                company: $('.company-drop-down'),
                shop: $('.shop-drop-down'),
                store: $('.store-drop-down')
            };

            $scope.dropdown.company.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                onChange: function (val) {
                    $scope.dropdown.shop.dropdown('clear');
                    $scope.dropdown.store.dropdown('clear');
                    shopDropDown(val);
                    storeDropDown(val);
                }
            });

            @if (isset($dailyStock) && $dailyStock)
                $scope.companyId = @json($dailyStock->company_id);
                shopDropDown($scope.companyId);
                storeDropDown($scope.companyId);
            @endif

            function shopDropDown(company) {
                var url = '{{ route('setting.shop.by.company.search', ['companyId']) }}';
                url = url.replace('companyId', company);
                $scope.dropdown.shop.dropdown('setting', {
                    forceSelection: false,
                    apiSettings: {
                        url: url + '/{query}',
                        cache:false,
                    },
                    saveRemoteData:false,
                    onChange: function(val, name){
                        $scope.query.van = val;
                    }
                });
            }

            function storeDropDown(company) {
                var url = '{{ route('setting.store.by.company.search', ['companyId']) }}';
                url = url.replace('companyId', company);
                $scope.dropdown.store.dropdown('setting', {
                    forceSelection: false,
                    apiSettings: {
                        url: url + '/{query}',
                        cache:false,
                    },
                    saveRemoteData:false,
                    onChange: function(val, name){
                        $scope.query.store = val;
                    }
                });
            }

        }]);
    </script>
@endsection
