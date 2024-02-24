<div class="form-body" ng-controller="StockController">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ ($errors->has('store_id')) ? 'has-danger' : '' }}">
                <label class="control-label"><b>FROM</b> | Store</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    <input name="store_id" type="hidden" value="{{ old('_token') ? old('store_id'): '' }}">
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
        <div class="col-md-3">
            <div class="form-group required {{ ($errors->has('sales_location')) ? 'has-danger' : '' }}">
                <label class="control-label"><b>TO</b> | Shop</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    <input name="sales_location" type="hidden" value="{{ old('_token') ? old('sales_location'): '' }}">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a shop</div>
                    <div class="menu">
                        @foreach(shopDropDown() as $key => $shop)
                            <div class="item" data-value="{{ $key }}">{{ $shop }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ ($errors->has('sales_location') ? $errors->first('sales_location') : '') }}</p>
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
            <div class="form-group required" ng-class="hasError('out_qty', $index) ? 'has-danger' : ''">
                <label class="control-label">Stock OUT qty</label>
                <input type="text" class="form-control" placeholder="enter out qty"
                       ng-class="hasError('out_qty', $index) ? 'error' : ''" name="out_qty[]">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required" ng-class="hasError('rate', $index) ? 'has-danger' : ''">
                <label class="control-label">Rate</label>
                <input type="text" class="form-control" placeholder="enter rate"
                       ng-class="hasError('rate', $index) ? 'error' : ''" name="rate[]">
            </div>
        </div>
        <div class="col-md-3">
            <button type="Button" ng-show="isRemoveable" ng-click="removeItem($index)"
                    class="btn btn-danger remove_row_btn pull-left m-t-20">
                <i class="fa fa-remove"></i>
            </button>
        </div>
    </div>
    <div class="row m-b-10 m-t-10">
        <div class="col-md-12">
            <button type="button" ng-click="addItem()" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>
                Add Another Item
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter stock out related notes here...', 'rows' => '3'], false) !!}
        </div>
    </div>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    @include('stock.out._inc.script')
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
