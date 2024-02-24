<div class="form-body" ng-controller="EstimateController">
    {{--<div class="row">--}}
        {{--<div class="col-md-3">
            <div class="form-group required {{ $errors->has('business_type_id') ? 'has-danger' : '' }}">
                <label class="control-label">Business type</label>
                <div class="ui fluid  search selection dropdown bt-drop-down {{ $errors->has('business_type_id') ? 'error' : '' }}">
                    <input type="hidden" name="business_type_id">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a business type</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('business_type_id') }}</p>
            </div>
        </div>--}}
    {{--</div>--}}
    {{--<hr>--}}
    <div class="row">
        <div class="col-md-3">
            <input type="hidden" name="business_type_id" value="1">
            <div class="form-group {{ $errors->has('customer_id') ? 'has-danger' : '' }}">
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
            <div class="form-group required {{ $errors->has('rep_id') ? 'has-danger' : '' }}">
                <label class="control-label">Sales rep</label>
                <div class="ui fluid  search selection dropdown rep-drop-down {{ $errors->has('rep_id') ? 'error' : '' }}">
                    <input type="hidden" name="rep_id">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a sales rep</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('rep_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            {!! form()->bsText('estimate_date', 'Estimate date', null, ['placeholder' => 'pick estimate date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('expiry_date', 'Expiry date', null, ['placeholder' => 'pick expiry date', 'class' => 'form-control datepicker']) !!}
        </div>
    </div>

    <h5 class="box-title box-title-with-margin">Estimate Line Items</h5>
    <hr>
    <div class="table-responsive po-line-items">
        <table class="table color-table inverse-table so-table">
            <thead>
            <tr>
                <th>Item</th>
                {{--<th style="width: 20%;">Store</th>--}}
                <th style="width: 10%;">Quantity</th>
                <th style="width: 12%;">Rate</th>
                <th style="width: 12%;">Discount</th>
                <th style="width: 12%;">Amount</th>
                <th style="width: 5%;"></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="product in estimate.product_items" product-loop>
                @include('sales.estimate._inc.item-template')
            </tr>
            <tr class="item-btn-container">
                <td colspan="6">
                    <button type="button" ng-click="addItem()" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Add Another Item</button>
                </td>
            </tr>
            <tr>
                <td colspan="7"><hr></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right vm"><b>Sub total</b></td>
                <td>@include('sales.estimate._inc.input', ['ngModel' => 'subTotal', 'name' => 'sub_total', 'placeHolder' => '0.00', 'readonly' => true, 'type' =>'number', 'class' => 'sub-total-input text-right'])</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right vm"><b>Discount</b></td>
                <td>
                    <div class="ui right labeled input">
                        <input name="discount_rate" placeholder="discount" ng-model="totalDiscount" ng-change="calculateTotal()" value="{{ old('discount_rate') ? old('discount_rate') : (isset($order) ? $order->discount_rate : '0.00') }}" style="width: 10%;" class="discount-input text-right {{ $errors->has('discount_rate') ? 'error' : '' }}">
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
                <td colspan="4" class="text-right vm"><b>Adjustment</b></td>
                <td>@include('sales.estimate._inc.input', ['ngModel' => 'totalAdjustment', 'ngChange' => "calculateTotal()", 'name' => 'adjustment', 'placeHolder' => '0.00', 'class' => 'adjustment-input text-right'])</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right vm"><b>Total</b></td>
                <td>@include('sales.estimate._inc.input', ['ngModel' => 'total',  'ngChange' => "calculateTotal()",  'name' => 'total', 'placeHolder' => '0.00', 'readonly' => true, 'class' => 'total-input text-right'])</td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
    <hr>
    <div class="row">
        <div class=" {{ isset($order) ? 'col-md-12' : 'col-md-8' }}">
            {!! form()->bsTextarea('terms', 'Terms & Conditions', null, ['placeholder' => 'enter estimate terms and conditions here...', 'rows' => '3'], false) !!}
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
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter estimate related notes here...', 'rows' => '4'], false) !!}
        </div>
    </div>
    @if(isset($inquiry) && $inquiry)
        <input type="hidden" name="inquiry_id" value="{{ $inquiry->id }}">
    @endif
</div>
@include('_inc.customer.add', ['dropdown'=> 'cus-drop-down', 'btn' => 'cus-drop-down-add-btn'])
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    @include('sales.estimate._inc.script')
@endsection