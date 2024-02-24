<div class="form-body" ng-controller="StockController">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('company_id') ? 'has-danger' : '' }}">
                <label class="control-label">Company</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('company_id') ? 'error' : '' }}">
                    @if(isset($route))
                        <input name="company_id" type="hidden" value="{{ old('_token') ? old('company_id'): $stock->company_id }}">
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
    </div>
    <div class="row">
        @if(!isset($stock))
        <div class="col-md-3">
            <div class="form-group {{ ($errors->has('production_unit')) ? 'has-danger' : '' }}">
                <label class="control-label"><b>FROM</b> | Production unit</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    @if(isset($stock))
                        <input name="store_id" type="hidden"
                               value="{{ old('_token') ? old('production_unit'): $stock->production_unit_id }}">
                    @else
                        <input name="production_unit" type="hidden" value="{{ old('_token') ? old('production_unit'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a production unit</div>
                    <div class="menu">
                        @foreach(productionUnitDropDown() as $key => $unit)
                            <div class="item" data-value="{{ $key }}">{{ $unit }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ ($errors->has('production_unit') ? $errors->first('production_unit') : '') }}</p>
            </div>
        </div>
        @endif
        <div class="col-md-3">
            <div class="form-group required {{ ($errors->has('store_id')) ? 'has-danger' : '' }}">
                @if(!isset($stock))
                <label class="control-label"><b>TO</b> | Store</label>
                @else
                <label class="control-label">Store</label>
                @endif
                <div class="ui fluid search normal selection dropdown drop-down">
                    @if(isset($stock))
                        <input name="store_id" type="hidden"
                               value="{{ old('_token') ? old('store_id'): $stock->store_id }}">
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
                <p class="form-control-feedback">{{ ($errors->has('store_id') ? $errors->first('store_id') : '') }}</p>
            </div>
        </div>
    </div>
    <div class="row m-b-10" ng-repeat="item in items" stock-loop>
        <div class="col-md-3">
            <div class="form-group required" ng-class="hasError('product_id', $index) ? 'has-danger' : ''">
                <label class="control-label">Product</label>
                <div class="ui fluid search normal selection dropdown product-drop-down"
                     ng-class="hasError('product_id', $index) ? 'error' : ''" data-index="@{{ $index }}">
                    <input name="product_id[]" type="hidden" value=""
                           ng-class="hasError('product_id', $index) ? 'error' : ''">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a product</div>
                    <div class="menu">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required" ng-class="hasError('available_stock', $index) ? 'has-danger' : ''">
                <label class="control-label">Qty</label>
                <input type="text" class="form-control" placeholder="enter stock in qty"
                       ng-class="hasError('quantity', $index) ? 'error' : ''" ng-model="item.available_stock"
                       name="available_stock[]">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group" ng-class="hasError('rate', $index) ? 'has-danger' : ''">
                <label class="control-label">Rate</label>
                <input type="text" class="form-control" placeholder="enter rate"
                       ng-class="hasError('rate', $index) ? 'error' : ''" name="rate[]" ng-model="item.rate">
            </div>
        </div>
        <div class="col-md-3">
            <button type="Button" ng-show="isRemoveable" ng-click="removeItem($index)"
                    class="btn btn-danger remove_row_btn pull-left m-t-20">
                <i class="fa fa-remove"></i>
            </button>
        </div>
    </div>
    @if(!isset($stock))
    <div class="row m-b-10 m-t-10">
        <div class="col-md-12">
            <button type="button" ng-click="addItem()" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>
                Add Another Item
            </button>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter stock in related notes here...', 'rows' => '3'], false) !!}
        </div>
    </div>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    @include('stock.list._inc.script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        var dropDown = $('.drop-down');
        dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });
    </script>
@endsection
