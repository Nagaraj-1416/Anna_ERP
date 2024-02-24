<div class="form-body m-t-15" ng-controller="SalesOrderController">
    <div class="row">
        <div class="col-md-6">
            <input type="hidden" name="business_type_id" value="1">
            <div class="form-group required {{ $errors->has('sales_type') ? 'has-danger' : '' }}">
                <label class="control-label">Sales type</label>
                <div class="demo-radio-button">
                    <input name="sales_type" value="Retail" type="radio" class="with-gap sales-type"
                           id="retail" {{ (!old('sales_type') && !isset($order)) ? 'checked' : '' }} {{ (old('sales_type') == 'Retail' || isset($order) && $order->sales_type == 'Retail') ? 'checked' : '' }}>
                    <label for="retail">Retail</label>
                    <input name="sales_type" value="Wholesale" type="radio" class="with-gap sales-type"
                           id="wholesale" {{ (old('sales_type') == 'Wholesale' || isset($order) && $order->sales_type == 'Wholesale') ? 'checked' : '' }}>
                    <label for="wholesale">Wholesale</label>
                    <input name="sales_type" value="Distribution" type="radio" class="with-gap sales-type"
                           id="Distribution" {{ (old('sales_type') == 'Distribution' || isset($order) && $order->sales_type == 'Distribution') ? 'checked' : '' }}>
                    <label for="Distribution">Distribution</label>
                </div>
                <p class="form-control-feedback">{{ $errors->first('sales_type') }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required {{ $errors->has('sales_category') ? 'has-danger' : '' }}">
                <label class="control-label">Office <b>OR</b> Van sales</label>
                <div class="demo-radio-button">
                    <input name="sales_category" value="Office" type="radio" class="with-gap sales-category" id="Office"
                           checked {{ (old('sales_category') == 'Office' || isset($order) && $order->sales_category == 'Office') ? 'checked' : '' }}>
                    <label for="Office">Office</label>
                    <input name="sales_category" value="Van" type="radio" class="with-gap sales-category"
                           id="Van" {{ (old('sales_category') == 'Van' || isset($order) && $order->sales_category == 'Van') ? 'checked' : '' }}>
                    <label for="Van">Van</label>
                </div>
                <p class="form-control-feedback">{{ $errors->first('sales_category') }}</p>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('customer_id') ? 'has-danger' : '' }}">
                <label class="control-label">Customer</label>
                <div class="ui fluid action input">
                    <div class="ui fluid search selection dropdown cus-drop-down {{ $errors->has('customer_id') ? 'error' : '' }}">
                        <input type="hidden" name="customer_id">
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a customer</div>
                        <div class="menu"></div>
                    </div>
                    <button type="button" class="ui blue right icon button" id="cus-drop-down-add-btn">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
                <p class="form-control-feedback">{{ $errors->first('customer_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            {!! form()->bsText('order_date', 'Order date', null, ['placeholder' => 'pick order date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('delivery_date', 'Delivery date', null, ['placeholder' => 'pick delivery date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3 schedule-date">
            {!! form()->bsText('scheduled_date', 'Schedule date', null, ['placeholder' => 'pick schedule date', 'class' => 'form-control datepicker']) !!}
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3 rep-panel" style="display: none;">
            <div class="form-group {{ $errors->has('rep_id') ? 'has-danger' : '' }}">
                <label class="control-label">Sales rep</label>
                <div class="ui fluid search selection dropdown rep-drop-down {{ $errors->has('rep_id') ? 'error' : '' }}">
                    <input type="hidden" name="rep_id">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a sales rep</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('rep_id') }}</p>
            </div>
        </div>
        {{--@if(showLocationDropdown())--}}
        <div class="col-md-3 sales-location-panel" style="display: none;">
            <div class="form-group {{ $errors->has('sales_location_id') ? 'has-danger' : '' }}">
                <label class="control-label">Sales location (Vehicle)</label>
                <div class="ui fluid search selection dropdown location-drop-down {{ $errors->has('sales_location_id') ? 'error' : '' }}">
                    <input type="hidden" name="sales_location_id">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a sales location</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('sales_location_id') }}</p>
            </div>
        </div>
        {{--@endif--}}
        <div class="col-md-3" ng-show="showPriceBook">
            <div class="form-group {{ $errors->has('price_book_id') ? 'has-danger' : '' }}">
                <label class="control-label">Price book</label>
                <div class="ui fluid action input">
                    <div class="ui fluid search selection dropdown pb-drop-down {{ $errors->has('price_book_id') ? 'error' : '' }}">
                        <input type="hidden" name="price_book_id">
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a price book</div>
                        <div class="menu"></div>
                    </div>
                    <button type="button" class="ui pink right icon button" id="reset-price-book">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
                <p class="form-control-feedback">{{ $errors->first('price_book_id') }}</p>
            </div>
        </div>

    </div>
    <h5 class="box-title box-title-with-margin">Sales Order Line Items </h5>
    <hr>
    <div class="po-line-items">
        <table class="table color-table inverse-table so-table">
            <thead>
            <tr>
                <th>Items & Description</th>
                <th style="width: 15%;">Store</th>
                <th style="width: 10%;">Quantity</th>
                <th style="width: 10%;">Rate</th>
                <th style="width: 10%;">Discount</th>
                <th style="width: 10%;">Amount</th>
                <th style="width: 5%;"></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="product in order.product_items" product-loop>
                @include('sales.order._inc.item-template')
            </tr>
            {{--<tr class="item-btn-container">
                <td>

                </td>
            </tr>--}}
            <tr class="item-btn-container">
                <td class="text-left vm">
                    <button type="button" ng-click="addItem()" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>
                        Add Another Item
                    </button>
                </td>
                <td colspan="4" class="text-right vm"><b>Sub total</b></td>
                <td>@include('sales.order._inc.input', ['ngModel' => 'subTotal', 'name' => 'sub_total', 'placeHolder' => '0.00', 'readonly' => true, 'type' =>'text', 'class' => 'sub-total-input text-right'])</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5" class="text-right vm"><b>Discount</b></td>
                <td>
                    <div class="ui right labeled input">
                        <input name="discount_rate" placeholder="discount" ng-model="totalDiscount"
                               ng-change="calculateTotal()"
                               value="{{ old('discount_rate') ? old('discount_rate') : (isset($order) ? $order->discount_rate : '0.00') }}"
                               style="width: 10%;"
                               class="discount-input text-right {{ $errors->has('discount_rate') ? 'error' : '' }}">
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
            </tr>
            <tr>
                <td colspan="5" class="text-right vm"><b>Adjustment</b></td>
                <td>@include('sales.order._inc.input', ['ngModel' => 'totalAdjustment', 'ngChange' => "calculateTotal()", 'name' => 'adjustment', 'placeHolder' => '0.00', 'class' => 'adjustment-input text-right'])</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5" class="text-right vm"><h4><b>Total</b></h4></td>
                <td>@include('sales.order._inc.input', ['ngModel' => 'total',  'ngChange' => "calculateTotal()",  'name' => 'total', 'placeHolder' => '0.00', 'readonly' => true, 'class' => 'total-input text-right'])</td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
    <hr>
    <div class="row">
        <div class=" {{ isset($order) ? 'col-md-12' : 'col-md-8' }}">
            {!! form()->bsTextarea('terms', 'Terms & Conditions', null, ['placeholder' => 'enter order terms and conditions here...', 'rows' => '3'], false) !!}
        </div>
        @if(!isset($order))
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Attach Files</label>
                    <input type="file" name="files[]" class="form-control" multiple id="fileUpload"
                           aria-describedby="fileHelp">
                    <p class="form-control-feedback">{{ $errors->first('files') }}</p>
                </div>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter order related notes here...', 'rows' => '4'], false) !!}
        </div>
    </div>
    @if(isset($inquiry) && $inquiry)
        <input type="hidden" name="inquiry_id" value="{{ $inquiry->id }}">
    @endif
    @if(isset($estimate) && $estimate)
        <input type="hidden" name="estimation_id" value="{{ $estimate->id }}">
    @endif
</div>
@include('_inc.customer.add', ['dropdown'=> 'cus-drop-down', 'btn' => 'cus-drop-down-add-btn'])
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    @include('sales.order._inc.script')
    @include('general.date.script', ['model' => isset($order) ? $order : null])
@endsection