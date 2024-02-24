<div class="row" id="barcode-input">
    <div class="col-md-12">
        <div class="alert alert-warning" role="alert" ng-show="barcodeError">
            @{{ barcodeError }}
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="item-selection-panel" style="padding-bottom: 20px !important;">
                    <div class="form-group">
                        <div class="demo-radio-button demo-radio-button-top">
                            <input name="order_mode" value="Cash" type="radio" class="with-gap order-mode"
                                   id="cash" {{ (old('order_mode') == 'Cash') ? 'checked' : '' }} checked>
                            <label for="cash"><b>CASH</b></label>
                            <input name="order_mode" value="Customer" type="radio" class="with-gap order-mode"
                                   id="customer" {{ (old('order_mode') == 'Customer') ? 'checked' : '' }}>
                            <label for="customer"><b>CREDIT</b></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row customer-panel" style="display: none;">
            <div class="col-md-12">
                <div class="item-selection-panel" style="padding-top: 0 !important; padding-bottom: 1px !important;">
                    <label class="control-label">CHOOSE CUSTOMER FOR CREDIT ORDER</label>
                    <div class="form-group {{ $errors->has('customer') ? 'has-danger' : '' }}">
                        <div class="ui fluid action input">
                            <div class="ui fluid normal search selection dropdown customer-drop-down {{ $errors->has('customer') ? 'error' : '' }}">
                                <input type="hidden" name="customer">
                                <i class="dropdown icon"></i>
                                <div class="default text">click here to search for customers...</div>
                                <div class="menu">
                                    @foreach(customerDropDown() as $key => $customer)
                                        <div class="item" data-value="{{ $key }}">{{ $customer }}</div>
                                    @endforeach
                                </div>
                            </div>
                            <button type="button" class="ui green right icon button" id="cus-drop-down-add-btn">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <p class="form-control-feedback" style="color: #940101;">{{ $errors->first('customer') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="item-selection-panel" style="padding-top: 0 !important;">
                    <label class="control-label">CHOOSE ITEMS <span style="font-weight: 600; font-size: 18px; color: #272c31;">OR</span> SCAN ITEMS USING BARCODE</label>
                    <div class="form-group">
                        <div class="ui fluid normal search selection dropdown item-drop-down m-b-10">
                            <input type="hidden" name="sales-item">
                            <i class="dropdown icon"></i>
                            <div class="default text">click here to search for items...</div>
                            <div class="menu"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>