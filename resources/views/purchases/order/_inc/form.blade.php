<div class="form-body" ng-controller="PurchaseOrderController">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required">
                <label class="control-label">purchase order for?</label>
                <div class="demo-radio-button">
                    <input name="po_for" value="PUnit" type="radio" class="with-gap po-for" id="PUnit" checked="" {{ (old('po_for') == 'PUnit' || (isset($order) && $order->po_for  == 'PUnit')) ? 'checked' : ''}}>
                    <label for="PUnit">PUnit</label>
                    <input name="po_for" value="Store" type="radio" class="with-gap po-for" id="Store" {{ (old('po_for') == 'Store' || (isset($order) && $order->po_for  == 'Store')) ? 'checked' : ''}}>
                    <label for="Store">Store</label>
                    <input name="po_for" value="Shop" type="radio" class="with-gap po-for" id="Shop" {{ (old('po_for') == 'Shop' || (isset($order) && $order->po_for  == 'Shop')) ? 'checked' : ''}}>
                    <label for="Shop">Shop</label>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row m-t-20">
        <div class="col-md-3">
            {!! form()->bsText('order_date', 'Order date', null, ['placeholder' => 'pick order date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-6">
            <div class="form-group required">
                <label class="control-label">Mode of purchase order</label>
                <div class="demo-radio-button">
                    <input name="po_mode" value="Internal" type="radio" class="with-gap" id="Internal" checked="" {{ (old('po_mode') == 'Internal' || (isset($order) && $order->po_mode  == 'Internal')) ? 'checked' : ''}}>
                    <label for="Internal">Internal</label>
                    <input name="po_mode" value="Virtual" type="radio" class="with-gap" id="Virtual" {{ (old('po_mode') == 'Virtual' || (isset($order) && $order->po_mode  == 'Virtual')) ? 'checked' : ''}}>
                    <label for="Virtual">Virtual</label>
                    <input name="po_mode" value="Outside" type="radio" class="with-gap" id="Outside" {{ (old('po_mode') == 'Outside' || (isset($order) && $order->po_mode  == 'Outside')) ? 'checked' : ''}}>
                    <label for="Outside">Outside</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('company_id') ? 'has-danger' : '' }}">
                <label class="control-label">Company</label>
                <div class="ui fluid search normal selection dropdown company-drop-down {{ $errors->has('company_id') ? 'error' : '' }}">
                    @if(isset($order))
                        <input name="company_id" type="hidden" value="{{ old('_token') ? old('company_id'): $order->company_id }}">
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
            <div class="form-group required {{ $errors->has('supplier_id') ? 'has-danger' : '' }}">
                <label class="control-label">Supplier</label>
                <div class="ui fluid search selection dropdown sup-drop-down {{ $errors->has('supplier_id') ? 'error' : '' }}">
                    <input type="hidden" name="supplier_id">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a supplier</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('supplier_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required unit-drop-panel {{ $errors->has('production_unit_id') ? 'has-danger' : '' }}">
                <label class="control-label">Production unit</label>
                <div class="ui fluid search selection dropdown unit-drop-down {{ $errors->has('production_unit_id') ? 'error' : '' }}">
                    @if(isset($order))
                        <input name="production_unit_id" type="hidden" value="{{ old('_token') ? old('production_unit_id'): $order->production_unit_id }}">
                    @else
                        <input name="production_unit_id" type="hidden" value="{{ old('_token') ? old('production_unit_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a production unit</div>
                    <div class="menu">
                        @foreach(productionUnitDropDown() as $key => $unit)
                            <div class="item" data-value="{{ $key }}">{{ $unit }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('production_unit_id') }}</p>
            </div>
            <div class="form-group required store-drop-panel {{ $errors->has('store_id') ? 'has-danger' : '' }}" style="display: none;">
                <label class="control-label">Store</label>
                <div class="ui fluid search selection dropdown store-drop-down {{ $errors->has('store_id') ? 'error' : '' }}">
                    @if(isset($order))
                        <input name="store_id" type="hidden" value="{{ old('_token') ? old('store_id'): $order->store_id }}">
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
            <div class="form-group required shop-drop-panel {{ $errors->has('shop_id') ? 'has-danger' : '' }}" style="display: none;">
                <label class="control-label">Shop</label>
                <div class="ui fluid search selection dropdown shop-drop-down {{ $errors->has('shop_id') ? 'error' : '' }}">
                    @if(isset($order))
                        <input name="shop_id" type="hidden" value="{{ old('_token') ? old('shop_id'): $order->shop_id }}">
                    @else
                        <input name="shop_id" type="hidden" value="{{ old('_token') ? old('shop_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a shop</div>
                    <div class="menu">
                        @foreach(shopDropDown() as $key => $shop)
                            <div class="item" data-value="{{ $key }}">{{ $shop }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('shop_id') }}</p>
            </div>
        </div>
    </div>

    <h5 class="box-title box-title-with-margin">Purchase order line items </h5>
    <hr>
    <div class="po-line-items">
        <table class="table color-table inverse-table po-table">
            <thead>
            <tr>
                <th>Item</th>
                {{--<th style="width: 20%;">Batch no</th>--}}
                <th style="width: 15%;">Quantity</th>
                {{--<th style="width: 15%;">Rate</th>--}}
                {{--<th style="width: 12%;">Discount</th>--}}
                {{--<th style="width: 12%;">Amount</th>--}}
                <th style="width: 5%;"></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="product in order.product_items" product-loop>
                @include('purchases.order._inc.item-template')
            </tr>
            <tr class="item-btn-container">
                <td colspan="2">
                    <button type="button" ng-click="addItem()" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Add Another Item</button>
                </td>
            </tr>
            <tr>
                <td colspan="2"><hr></td>
            </tr>
            {{--<tr>
                <td colspan="1" class="text-right vm"><b>Sub total</b></td>
                <td>@include('purchases.order._inc.input', ['ngModel' => 'subTotal', 'name' => 'sub_total', 'placeHolder' => '0.00', 'readonly' => true, 'class' => 'sub-total-input text-right'])</td>
                <td></td>
            </tr>--}}
            {{--<tr>
                <td colspan="5" class="text-right vm"><b>Discount</b></td>
                <td>
                    <div class="ui right labeled input">
                        <input name="discount_rate" placeholder="discount" ng-model="totalDiscount" ng-change="calculateTotal()" value="{{ old('discount_rate') ? old('discount_rate') : (isset($order) ? $order->discount_rate : '0.00') }}" style="width: 10%;" class="discount-input {{ $errors->has('discount_rate') ? 'error' : '' }} text-right form-control">
                        <div class="ui dropdown label discount-type">
                            <input type="hidden" name="discount_type" value="Amount">
                            <div class="text">LKR</div>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <div data-value="Percentage" class="item">%</div>
                                <div data-value="Amount" class="item">LKR</div>
                            </div>
                        </div>
                    </div>
                </td>
                <td></td>
            </tr>--}}
            {{--<tr>
                <td colspan="5" class="text-right vm"><b>Adjustment</b></td>
                <td>@include('purchases.order._inc.input', ['ngModel' => 'totalAdjustment', 'ngChange' => "calculateTotal()", 'name' => 'adjustment', 'placeHolder' => '0.00', 'class' => 'adjustment-input text-right'])</td>
                <td></td>
            </tr>--}}
            {{--<tr>
                <td class="text-right vm"><b>Total</b></td>
                <td>@include('purchases.order._inc.input', ['ngModel' => 'total',  'ngChange' => "calculateTotal()",  'name' => 'total', 'placeHolder' => '0.00', 'readonly' => true, 'class' => 'total-input text-right'])</td>
                <td></td>
            </tr>--}}
            </tbody>
        </table>
    </div>
    <hr>
    <div class="row">
        <div class=" {{ isset($order) ? 'col-md-12' : 'col-md-8' }}">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter order related notes here...', 'rows' => '4'], false) !!}
        </div>
        @if(!isset($order))
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Attach Files</label>
                <input type="file" name="files[]" class="form-control" multiple id="fileUpload" aria-describedby="fileHelp">
                <p class="form-control-feedback">{{ $errors->first('files') }}</p>
            </div>
        </div>
        @endif
    </div>
    {{--<div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter order related notes here...', 'rows' => '4'], false) !!}
        </div>
    </div>--}}
</div>
{{--@include('_inc.brand.add', ['dropdown'=> 'cus-drop-down', 'btn' => 'cus-drop-down-add-btn'])--}}
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    @parent
    @include('purchases.order._inc.script')
    @include('general.date.script', ['model' => isset($order) ? $order : null])
@endsection