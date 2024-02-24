<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-body printableArea">
                <h3>
                    <b>PURCHASE ORDER</b> |
                    <small class="{{ statusLabelColor($order->status) }}">
                        {{ $order->status }}
                    </small>
                    <span class="pull-right">#{{ $order->po_no }}</span>
                    <input type="hidden" value="{{ $order->id }}" name="purchase_order_id">
                </h3>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                            <address>
                                <h4><b class="text-danger">{{ $company->name }}</b></h4>
                                @include('_inc.address.view', ['address' => $companyAddress])
                            </address>
                        </div>
                        <div class="pull-right text-right">
                            <address>
                                <h4 class="font-bold">{{ $supplier->display_name }}</h4>
                                @if($supplierAddress)
                                    @include('_inc.address.view', ['address' => $supplierAddress])
                                @endif
                            </address>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="pull-left">
                            <p><b>Order Date :</b> {{ $order->order_date }}</p>
                        </div>
                        <div class="pull-right text-right">
                            <p><b>Purchase mode :</b> {{ $order->po_mode }}</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table color-table inverse-table" style="width: 100%; max-width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 2px;">#</th>
                                    <th>Items & Description</th>
                                    <th class="text-center" style="width: 10%;">Requested Qty</th>
                                    <th class="text-center" style="width: 10%;">Issuing Qty</th>
                                    @if(isDirectorLevelStaff() || isAccountLevelStaff())
                                    <th class="text-right" style="width: 10%;">Rate</th>
                                    <th class="text-right" style="width: 10%;">Amount</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($items))
                                @foreach($items as $itemKey => $item)
                                    <tr class="item-row">
                                        <td style="width: 2px;">
                                            <div class="demo-checkbox">
                                                <input type="checkbox" id="{{ 'md_checkbox_29_' . $itemKey }}"
                                                       name="products[product_id][{{ $item->pivot->product_id }}]"
                                                       class="item-chk-col-cyan" value="{{ $item->pivot->product_id }}"
                                                       data-id="{{ $item->pivot->product_id }}"
                                                       data-value="{{ $item->pivot->quantity * $item->purchase_price }}">
                                                <label style="min-width: 35px !important;" for="{{ 'md_checkbox_29_' . $itemKey }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <b>{{ $item->name }}</b> <br /> <br />
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" placeholder="batch no" name="products[batch_no][{{ $item->pivot->product_id }}]">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control datepicker" placeholder="manufacture" name="products[manufacture][{{ $item->pivot->product_id }}]">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control datepicker" placeholder="expiry date" name="products[expiry][{{ $item->pivot->product_id }}]">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <select class="ui fluid normal dropdown packing-type-drop-down" name="products[packing_type][{{ $item->pivot->product_id }}]">
                                                            <option value="">choose a type</option>
                                                            <option value="Cardboard Box">Cardboard Box</option>
                                                            <option value="Fertilizer Bag">Fertilizer Bag</option>
                                                            <option value="Paper Bag">Paper Bag</option>
                                                            <option value="Polythene Bag">Polythene Bag</option>
                                                            <option value="Mailer Box">Mailer Box</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" placeholder="brand" name="products[brand][{{ $item->pivot->product_id }}]">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" placeholder="grade" name="products[grade][{{ $item->pivot->product_id }}]">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" placeholder="color" name="products[color][{{ $item->pivot->product_id }}]">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" placeholder="no of bags" name="products[no_of_bags][{{ $item->pivot->product_id }}]">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center" style="vertical-align: bottom">
                                            <input type="text" class="form-control text-center item-requested-qty" name="products[quantity][{{ $item->pivot->product_id }}]" value="{{ $item->pivot->quantity }}" readonly/>
                                        </td>
                                        <td class="text-center" style="vertical-align: bottom">
                                            <input type="text" class="form-control text-center item-issue-qty" name="products[issued_qty][{{ $item->pivot->product_id }}]" value="{{ old('_token') ? old('products.issued_qty.'.$item->pivot->product_id): '' }}" data-value="{{ $item->purchase_price }}" placeholder="issuing" readonly/>
                                        </td>
                                        @if(isDirectorLevelStaff() || isAccountLevelStaff())
                                        <td style="vertical-align: bottom;">
                                            <input type="text" class="form-control text-right" placeholder="rate" readonly value="{{ $item->purchase_price }}" name="products[rate][{{ $item->pivot->product_id }}]"/>
                                        </td>
                                        <td style="vertical-align: bottom;">
                                            <input type="text" class="form-control text-right item-amount" placeholder="amount" readonly value="{{ $item->pivot->quantity * $item->purchase_price }}" name="products[amount][{{ $item->pivot->product_id }}]"/>
                                        </td>
                                        @else
                                            <input type="hidden" class="form-control text-right" placeholder="rate" readonly value="{{ $item->purchase_price }}" name="products[rate][{{ $item->pivot->product_id }}]"/>
                                            <input type="hidden" class="form-control text-right item-amount" placeholder="amount" readonly value="{{ $item->pivot->quantity * $item->purchase_price }}" name="products[amount][{{ $item->pivot->product_id }}]"/>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                        <br /><br />
                        <div class="card card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="box-title">Other Details</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    {!! form()->bsText('loaded_by', 'Loaded By', null, ['placeholder' => 'loaded by'], false) !!}
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group required" style="padding-bottom: 15px;">
                                                        <label class="control-label">Transfer by</label>
                                                        <div class="demo-radio-button">
                                                            <input name="transfer_by" value="Internal" type="radio" class="with-gap transfer-by" id="Internal" checked="" {{ (old('transfer_by') == 'Internal' || (isset($grn) && $grn->transfer_by  == 'Internal')) ? 'checked' : ''}}>
                                                            <label for="Internal">Internal</label>
                                                            <input name="transfer_by" value="OwnVehicle" type="radio" class="with-gap transfer-by" id="OwnVehicle" {{ (old('transfer_by') == 'OwnVehicle' || (isset($grn) && $grn->transfer_by  == 'OwnVehicle')) ? 'checked' : ''}}>
                                                            <label for="OwnVehicle">Own Vehicle</label>
                                                            <input name="transfer_by" value="HiredVehicle" type="radio" class="with-gap transfer-by" id="HiredVehicle" {{ (old('transfer_by') == 'HiredVehicle' || (isset($grn) && $grn->transfer_by  == 'HiredVehicle')) ? 'checked' : ''}}>
                                                            <label for="HiredVehicle">Hired Vehicle</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="own-vehicle-panel" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group {{ $errors->has('vehicle_id') ? 'has-danger' : '' }}">
                                                            <label class="control-label">Vehicle</label>
                                                            <div class="ui fluid search normal selection dropdown vehicle-drop-down {{ $errors->has('vehicle_id') ? 'error' : '' }}">
                                                                @if(isset($grn))
                                                                    <input name="vehicle_id" type="hidden" value="{{ old('_token') ? old('vehicle_id'): $grn->vehicle_id }}">
                                                                @else
                                                                    <input name="vehicle_id" type="hidden" value="{{ old('_token') ? old('vehicle_id'): '' }}">
                                                                @endif
                                                                <i class="dropdown icon"></i>
                                                                <div class="default text">choose a vehicle</div>
                                                                <div class="menu">
                                                                    @foreach(vehicleDropDown() as $key => $vehicle)
                                                                        <div class="item" data-value="{{ $key }}">{{ $vehicle }}</div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <p class="form-control-feedback">{{ $errors->first('vehicle_id') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group {{ $errors->has('driver') ? 'has-danger' : '' }}">
                                                            <label class="control-label">Driver</label>
                                                            <div class="ui fluid search normal selection dropdown driver-drop-down {{ $errors->has('driver') ? 'error' : '' }}">
                                                                @if(isset($grn))
                                                                    <input name="driver" type="hidden" value="{{ old('_token') ? old('driver'): $grn->driver }}">
                                                                @else
                                                                    <input name="driver" type="hidden" value="{{ old('_token') ? old('driver'): '' }}">
                                                                @endif
                                                                <i class="dropdown icon"></i>
                                                                <div class="default text">choose a driver</div>
                                                                <div class="menu">
                                                                    @foreach(driverDropDown() as $key => $driver)
                                                                        <div class="item" data-value="{{ $key }}">{{ $driver }}</div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <p class="form-control-feedback">{{ $errors->first('vehicle_id') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group {{ $errors->has('helper') ? 'has-danger' : '' }}">
                                                            <label class="control-label">Helper</label>
                                                            <div class="ui fluid search normal selection dropdown helper-drop-down {{ $errors->has('helper') ? 'error' : '' }}">
                                                                @if(isset($grn))
                                                                    <input name="helper" type="hidden" value="{{ old('_token') ? old('helper'): $grn->helper }}">
                                                                @else
                                                                    <input name="helper" type="hidden" value="{{ old('_token') ? old('helper'): '' }}">
                                                                @endif
                                                                <i class="dropdown icon"></i>
                                                                <div class="default text">choose a helper</div>
                                                                <div class="menu">
                                                                    @foreach(helperDropDown() as $key => $helper)
                                                                        <div class="item" data-value="{{ $key }}">{{ $helper }}</div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <p class="form-control-feedback">{{ $errors->first('helper') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        {!! form()->bsText('odo_starts_at', 'ODO Starts at', null, ['placeholder' => 'starts at'], false) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="hired-vehicle-panel" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        {!! form()->bsText('vehicle_no', 'Vehicle No', null, ['placeholder' => 'vehicle no'], false) !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        {!! form()->bsText('transport_name', 'Transport name', null, ['placeholder' => 'transport name'], false) !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        {!! form()->bsText('driver_name', 'Driver Name', null, ['placeholder' => 'driver name'], false) !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        {!! form()->bsText('helper_name', 'Helper name', null, ['placeholder' => 'helper name'], false) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="col-md-6">
                                    <h4 class="box-title">Bill Details</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {!! form()->bsText('bill_amount', 'Bill Amount', null, ['placeholder' => 'bill amount', 'readonly' => 'readonly', 'ng-model' => 'billAmount', 'class' => 'form-control bill-amount'], false) !!}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {!! form()->bsText('bill_date', 'Bill Date', null, ['placeholder' => 'bill date', 'class' => 'form-control datepicker'], false) !!}
                                                </div>
                                                <div class="col-md-6">
                                                    {!! form()->bsText('due_date', 'Bill Due', null, ['placeholder' => 'bill due', 'class' => 'form-control datepicker'], false) !!}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter bill related notes here...', 'rows' => '4'], false) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
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
    @include('purchases.grn._inc.script')
    @include('general.date.script', ['model' => isset($order) ? $order : null])
@endsection