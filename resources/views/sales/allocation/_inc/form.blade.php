<div class="form-body">
    <div ng-show="getError('duplicate')" class="">
        <div class=" alert alert-danger">
            <h5 class="text-danger">
                <i class="fa fa-exclamation-circle"></i>
                @{{ getError('duplicate') }}
            </h5>
        </div>
    </div>
    <div>

    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('day_type') ? 'has-danger' : '' }}">
                <label class="control-label">Sales duration</label>
                <div class="demo-radio-button">
                    <input name="day_type" value="Single" type="radio" class="with-gap day-type"
                           ng-click="handleDateTypeChange('Single')"
                           id="Single" {{ (!old('day_type') && !isset($allocation)) ? 'checked' : '' }} {{ (old('day_type') == 'Single' || isset($allocation) && $allocation->day_type == 'Single') ? 'checked' : '' }}>
                    <label for="Single">Single Day</label>
                    <input name="day_type" value="Multiple" type="radio" class="with-gap day-type"
                           ng-click="handleDateTypeChange('Multiple')"
                           id="Multiple" {{ (old('day_type') == 'Multiple' || isset($allocation) && $allocation->day_type == 'Multiple') ? 'checked' : '' }}>
                    <label for="Multiple">Multiple Days</label>
                </div>
                <p class="form-control-feedback">{{ $errors->first('day_type') }}</p>
            </div>
        </div>
        {{--<div class="col-md-3">
            {!! form()->bsText('from_date', 'From date', null, ['placeholder' => 'pick a from date', 'class' => 'form-control datepicker from-date', 'ng-model' => 'query.fromDate', 'ng-change' => 'getDrivers()']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('to_date', 'To date', null, ['placeholder' => 'pick a to date', 'class' => 'form-control datepicker to-date', 'ng-model' => 'query.toDate', 'ng-change' => 'getDrivers()']) !!}
        </div>
        <input type="hidden" name="duplicate">--}}
    </div>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('from_date', 'From date', null, ['placeholder' => 'pick a from date', 'class' => 'form-control datepicker from-date', 'ng-model' => 'query.fromDate', 'ng-change' => 'getDrivers()']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('to_date', 'To date', null, ['placeholder' => 'pick a to date', 'class' => 'form-control datepicker to-date', 'ng-model' => 'query.toDate', 'ng-change' => 'getDrivers()']) !!}
        </div>
        <input type="hidden" name="duplicate">
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('sales_location') ? 'has-danger' : '' }}">
                <label class="control-label">Sales location</label>
                <div class="demo-radio-button">
                    <input name="sales_location" value="Van" type="radio" class="with-gap sales-location"
                           ng-click="handleLocationTypeChange('Van')"
                           id="Van" {{ (!old('sales_location') && !isset($allocation)) ? 'checked' : '' }} {{ (old('sales_location') == 'Van' || isset($allocation) && $allocation->sales_location == 'Van') ? 'checked' : '' }}>
                    <label for="Van">Van</label>
                    <input name="sales_location" value="Shop" type="radio" class="with-gap sales-location"
                           ng-click="handleLocationTypeChange('Shop')"
                           id="Shop" {{ (old('sales_location') == 'Shop' || isset($allocation) && $allocation->sales_location == 'Shop') ? 'checked' : '' }}>
                    <label for="Shop">Shop</label>
                </div>
                <p class="form-control-feedback">{{ $errors->first('sales_location') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('sales_location_id') ? 'has-danger' : '' }}">
                <label class="control-label">Sales location</label>
                <div class="ui fluid search normal selection dropdown sales-drop-down {{ $errors->has('sales_location_id') ? 'error' : '' }}">
                    @if(isset($allocation))
                        <input name="sales_location_id" type="hidden"
                               value="{{ old('_token') ? old('sales_location_id'): $allocation->sales_location_id }}">
                    @else
                        <input name="sales_location_id" type="hidden">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a sales location</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('sales_location_id') }}</p>
            </div>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
    </div>
    <div class="row van-details-panel" ng-show="query.locationType === 'Van'">
        {{--<div class="col-md-3"></div>--}}
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('rep_id') ? 'has-danger' : '' }}">
                <label class="control-label">Rep</label>
                <div class="ui fluid search normal selection dropdown rep-drop-down {{ $errors->has('rep_id') ? 'error' : '' }}">
                    @if(isset($allocation))
                        <input name="rep_id" type="hidden"
                               value="{{ old('_token') ? old('rep_id'): $allocation->rep_id }}">
                    @else
                        <input name="rep_id" type="hidden" value="{{ old('_token') ? old('rep_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a sales rep</div>
                    <div class="menu">
                        {{--@foreach(repDropDown() as $key => $rep)--}}
                        {{--<div class="item" data-value="{{ $key }}">{{ $rep }}</div>--}}
                        {{--@endforeach--}}
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('rep_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('route_id') ? 'has-danger' : '' }}">
                <label class="control-label">Route</label>
                <div class="ui fluid search normal selection dropdown drop-down route-drop-down {{ $errors->has('route_id') ? 'error' : '' }}">
                    @if(isset($allocation))
                        <input name="route_id" type="hidden"
                               value="{{ old('_token') ? old('route_id'): $allocation->route_id }}">
                    @else
                        <input name="route_id" type="hidden" value="{{ old('_token') ? old('route_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a route</div>
                    <div class="menu">
                        {{--@foreach(routeDropDown() as $key => $route)--}}
                        {{--<div class="item" data-value="{{ $key }}">{{ $route }}</div>--}}
                        {{--@endforeach--}}
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('route_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            {!! form()->bsText('odo_meter_reading', 'Odo meter start reading', null, ['placeholder' => 'enter the odo meter start reading', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="row m-t-10">
        {{--<div class="col-md-3"></div>--}}
        <div class="col-md-3" ng-show="query.locationType === 'Van'">
            <div class="form-group required {{ $errors->has('driver_id') ? 'has-danger' : '' }}">
                <label class="control-label">Driver</label>
                <div class="ui fluid search normal selection dropdown driver-drop-down {{ $errors->has('driver_id') ? 'error' : '' }}">
                    @if(isset($allocation))
                        <input name="driver_id" type="hidden"
                               value="{{ old('_token') ? old('driver_id'): $allocation->driver_id }}">
                    @else
                        <input name="driver_id" type="hidden" value="{{ old('_token') ? old('driver_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a driver</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('driver_id') }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group {{ $errors->has('labour_id') ? 'has-danger' : '' }}">
                <label class="control-label">Labours</label>
                <div class="ui fluid search normal selection multiple dropdown labour-drop-down {{ $errors->has('labour_id') ? 'error' : '' }}">
                    @if(isset($allocation))
                        <input name="labour_id" type="hidden"
                               value="{{ old('_token') ? old('labour_id'): $allocation->labour_id }}">
                    @else
                        <input name="labour_id" type="hidden" value="{{ old('_token') ? old('labour_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose labours</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('labour_id') }}</p>
            </div>
        </div>

        <div class="col-md-3">
            {!! form()->bsText('allowance', 'Allowance', null, ['placeholder' => 'enter the allowance value', 'class' => 'form-control', 'ng-model' => 'query.allowance']) !!}
        </div>
    </div>

    <div ng-show="query.locationType === 'Van'">
        <hr>
        <div class="pull-left">
            <h4 class="box-title">Customers
                <small class="text-megna">(Pick customers to associate with this allocation)</small>
            </h4>
            <h6 style="padding-top: 2px;">
                <small class="error">@{{ getError('customer') }}</small>
            </h6>
        </div>
        <div class="pull-right">
            <h4 class="box-title text-warning">Selected Customers : @{{ getCustomersCount() }}
            </h4>
        </div>
        <div class="clrearfix">

        </div>
        <div class="m-b-10 m-t-5">
            <input type="text" style="margin-left: 0 !important;" ng-model="customerSearch"
                   placeholder="type your keywords here and search for customers" class="form-control"
                   autocomplete="off">
        </div>


        <div id="customer-section">
            <table class="ui table bordered celled table-scroll">
                <thead>
                <tr>
                    <th style="width: 3%;">
                        <input type="checkbox" id="customer_select_all"
                               name="customer_select_all"
                               class="chk-col-cyan customer-check"
                               ng-click="handleCustomerCheckAll($event)" {{ old('customer_select_all') ? 'checked' : '' }}>
                        <label for="customer_select_all"></label>
                    </th>
                    <th>Customer details</th>
                </tr>
                </thead>
                <tbody>
                <tr class="@{{ oldCustomers.hasOwnProperty(customer.id) ? 'td-bg-danger' : '' }}"
                    ng-repeat="(key, customer) in customers | filter:customerSearch" customer-directive>
                    <td style="width: 3%;">
                        <div class="demo-checkbox">
                            <input type="checkbox" id="@{{ 'md_checkbox_28_' + customer.id }}"
                                   name="customer[id][@{{ customer.id }}]"
                                   class="chk-col-cyan customer-check"
                                   data-customer="@{{ customer.id }}" ng-click="customerCheckBoxChanged(customer.id)">
                            <label for="@{{ 'md_checkbox_28_' + customer.id }}"></label>
                        </div>
                    </td>
                    <td>@{{ customer.display_name }} - (@{{ customer.full_name }} | @{{ customer.mobile }})</td>
                </tr>
                <tr ng-if="!customers.length">
                    <td colspan="3" class="text-warning">No customers found, please choose a route to load customers
                        list.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <hr>

    <div class="pull-left">
        <h4 class="box-title">Products
            <small class="text-megna">(Pick products to associate with this allocation)</small>
        </h4>
        <h6 style="padding-top: 2px;">
            <small class="error">@{{ getError('product') }}</small>
            <small class="error">@{{ oldData.qty_count ? 'There are ' + oldData.qty_count + ' products in 0 quantity' :
                ''}}
            </small>
        </h6>
    </div>
    <div class="pull-right">
        <h4 class="box-title text-warning">Selected Products : @{{ getProductsCount() }}
        </h4>
    </div>
    <div class="clrearfix">

    </div>
    <div class="m-b-10 m-t-5">
        <input type="text" style="margin-left: 0 !important;" ng-model="productSearch"
               placeholder="type your keywords here and search for products" class="form-control" autocomplete="off">
    </div>
    <div id="product-section">
        <table class="ui table bordered celled striped table-scroll">
            <thead>
            <tr>
                <th style="width: 3%;">
                    <input type="checkbox" id="product_select_all"
                           name="product_select_all"
                           class="chk-col-cyan "
                           ng-click="handleProductCheckAll($event)" {{ old('product_select_all') ? 'checked' : '' }}>
                    <label for="product_select_all"></label>
                </th>
                <th style="width: 10%;">Product details</th>
                <th style="width: 10%;">Quantity</th>
                <th style="width: 10%;">CF Qty</th>
                <th style="width: 10%;">Default Qty</th>
                <th>Store</th>

            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="(key, product) in products | filter:productSearch" product-directive
                class="product-@{{ product.id }}">
                <td style="width: 3%;">
                    <div class="demo-checkbox">
                        <input type="checkbox" id="@{{ 'md_checkbox_28_product_' + key }}"
                               name="product[id][@{{ product.id }}]"
                               class="chk-col-cyan product-check "
                               data-product="@{{ product.id }}" ng-click="productCheckBoxChanged(product.id)">
                        <label for="@{{ 'md_checkbox_28_product_' + key }}"></label>
                    </div>
                    <input type="hidden" class="support-input" value="true" name="product[id][@{{ product.id }}]"
                           disabled="disabled">
                </td>
                <td style="width: 10%;">
                    @{{ product.name }}
                </td>
                <td style="width: 10%;">
                    <div ng-show="isChecked(query.productsChecked, product.id)">
                        <input type="text" class="form-control quantity-text"
                               ng-class="(hasError('product.quantity.'+product.id) || hasError('product.id.'+product.id)) ? 'error' : ''"
                               name="product[quantity][@{{ product.id }}]" ng-model="product.pivot.default_qty"
                               ng-change="updateDefaultQty(product)">
                        <p class="help-block error"
                           ng-show="isChecked(query.productsChecked, product.id) && getError('product.id.'+product.id)">
                            @{{ getError('product.id.'+product.id) }}
                        </p>
                        <p class="help-block error"
                           ng-show="isChecked(query.productsChecked, product.id) && getError('product.quantity.'+product.id)">
                            @{{ getError('product.quantity.'+product.id) }}
                        </p>
                    </div>
                </td>
                <td style="width: 10%;" class="text-right">
                    <input type="text" disabled class="form-control" name="product[cf][@{{ product.id }}]"
                           value=" @{{ product.cf_qty }}">
                </td>
                <td style="width: 10%;" class="text-right">
                    <input type="text" disabled class="form-control" name=""
                           value=" @{{ product.default_quantity }}">
                </td>
                <td>
                    <div class="form-group required" ng-show="isChecked(query.productsChecked, product.id)">
                        <div class="ui fluid  search selection dropdown store-drop-down"
                             ng-class="hasError('product.store.'+product.id) ? 'error' : ''"
                             data-index="@{{ product.id }}">
                            <input type="hidden" name="product[store][@{{ product.id }}]"
                                   ng-class="hasError('store', product.id) ? 'error' : ''">
                            <i class="dropdown icon"></i>
                            <div class="default text">choose a store</div>
                            <div class="menu"></div>
                        </div>
                    </div>
                    <p class="help-block error">@{{ getError('product.store.'+product.id) }}</p>
                </td>
            </tr>
            <tr ng-if="!products.length">
                <td colspan="5" class="text-warning">No products found, please choose a route to load products list.
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter allocation related notes here...', 'rows' => '4'], false) !!}
        </div>
    </div>

    <input type="hidden" value="{{ isset($allocation) ? $allocation->id : '' }}" name="id">
    <input type="hidden" value="{{ isset($allocation) ? $allocation->id : '' }}" name="allocation">

    <input type="hidden" name="start_time"
           value="{{ isset($startTime) ? $startTime->toDateTimeString() : carbon()->now()->toDateTimeString()}}">
    <input type="hidden" name="end_time" value="" id="endTimeInput">
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
    <style>
        .error {
            color: red;
        }

        #customer-section {
            overflow-x: hidden !important;
            max-height: 500px !important;
            height: 500px !important;
            width: 100% !important;
        }

        #product-section {
            overflow-x: hidden !important;
            max-height: 500px !important;
            height: 500px !important;
            width: 100% !important;
        }

        .mCSB_container.mCS_x_hidden.mCS_no_scrollbar_x {

        }
    </style>
@endsection

@section('script')
    @parent
    @include('sales.allocation._inc.script')
@endsection