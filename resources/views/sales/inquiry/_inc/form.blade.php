<div class="form-body" ng-controller="InquiryController">
    <div class="row">
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
            {!! form()->bsText('inquiry_date', 'Inquiry date', null, ['placeholder' => 'pick inquiry date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Attach files</label>
                <input type="file" name="files[]" class="form-control" multiple id="fileUpload" aria-describedby="fileHelp">
                <p class="form-control-feedback">{{ $errors->first('files') }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('description', 'Notes', null, ['placeholder' => 'enter inquiry related notes here...', 'rows' => '4'], false) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5 class="box-title box-title-with-margin">Sales Inquiry Line Items</h5>
            <hr>
            <div class="table-responsive po-line-items overflow-fixed">
                <table class="table color-table inverse-table so-table">
                    <thead>
                    <tr>
                        <th>Items</th>
                        <th>Notes</th>
                        <th style="width: 10%;">Quantity</th>
                        <th style="width: 12%;">Delivery date</th>
                        <th style="width: 5%;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="product in inquiry.product_items" inquiry-loop>
                        @include('sales.inquiry._inc.item-template')
                    </tr>
                    <tr class="item-btn-container">
                        <td colspan="6">
                            <button type="button" ng-click="addItem()" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Add Another Item</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('_inc.customer.add', ['dropdown'=> 'cus-drop-down', 'btn' => 'cus-drop-down-add-btn'])
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    @include('sales.inquiry._inc.script')
@endsection