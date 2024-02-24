<div class="form-body" ng-controller="ExpenseFormController">
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('expense_date', 'Expense date', null, ['placeholder' => 'pick expense date', 'class' => 'form-control datepicker', 'ng-model' => 'todayDate']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('expense_time', 'Expense time', null, ['placeholder' => 'pick expense time', 'class' => 'form-control clockpicker', 'ng-model' => 'currentTime']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('amount', 'Amount', null, ['placeholder' => 'enter expense amount']) !!}
        </div>
    </div>
    <hr />
    {{--<hr>--}}
    {{--<div class="row">
        <div class="col-md-12">
            <div class="form-group required">
                <label class="control-label">Payment mode</label>
                <div class="demo-radio-button">
                    <input name="payment_mode" value="Cash" type="radio" class="with-gap cash payment-mode"
                           id="Cash"
                           checked="" {{ (old('payment_mode') == 'Cash') || (isset($expense) && $expense->payment_mode == 'Cash') ? 'checked' : ''}}>
                    <label for="Cash">Cash</label>
                    <input name="payment_mode" value="Bank" type="radio" class="with-gap bank payment-mode"
                           id="Bank" {{ (old('payment_mode') == 'Bank') || (isset($expense) && $expense->payment_mode == 'Bank' ) ? 'checked' : ''}}>
                    <label for="Bank">Bank</label>
                    <input name="payment_mode" value="Cheque" type="radio" class="with-gap cheque payment-mode"
                           id="Cheque" {{ (old('payment_mode') == 'Cheque') || (isset($expense) && $expense->payment_mode == 'Cheque' ) ? 'checked' : ''}}>
                    <label for="Cheque">Cheque</label>
                </div>
            </div>
            <input type="hidden" value="Cash" class="payment-mode-hidden">
        </div>
    </div>--}}
    {{--@include('expense.receipt._inc.payment')--}}
    {{--<hr>--}}
    <div class="row m-t-20">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('type_id') ? 'has-danger' : '' }}">
                <label class="control-label">Expense type</label>
                <div class="ui fluid action input">
                    <div class="ui fluid  search selection dropdown expense-type-drop-down {{ $errors->has('type_id') ? 'error' : '' }} {{ isset($expense) && $expense ? 'disabled' : '' }}">
                        @if(isset($expense))
                            <input name="type_id" type="hidden" value="{{ old('_token') ? old('type_id'): $expense->type_id }}">
                        @else
                            <input name="type_id" type="hidden" value="{{ old('_token') ? old('type_id'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a type</div>
                        <div class="menu">
                            @foreach(expenseTypesDropDown() as $key => $expType)
                                <div class="item" data-value="{{ $key }}">{{ $expType }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('type_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            {{--<div class="form-group paid-through-form-panel required {{ $errors->has('paid_through') ? 'has-danger' : '' }}">
                <label class="control-label">Paid through</label>
                <div class="ui fluid  search selection dropdown paid-through-drop-down {{ $errors->has('paid_through') ? 'error' : '' }}">
                    @if(isset($expense))
                        <input name="paid_through" type="hidden" value="{{ old('_token') ? old('paid_through'): $expense->paid_through }}">
                    @else
                        <input name="paid_through" type="hidden" value="{{ old('_token') ? old('paid_through'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a paid through account</div>
                    <div class="menu"></div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('paid_through') }}</p>
            </div>--}}
        </div>
    </div>

    <div class="additional-data" style="display: none;">
        <div class="row">
            <!-- month field -->
            <!-- Salary, Salary Advance, Bonus, Commission -->
            <!-- EPF, ETF, NBT, Vat, Rent -->
            <div class="col-md-3 month-field" style="display: none;">
                <div class="form-group required {{ $errors->has('month') ? 'has-danger' : '' }}">
                    <label class="control-label">Payment Month</label>
                    <div class="ui fluid search normal selection dropdown month-drop-down {{ $errors->has('month') ? 'error' : '' }}">
                        @if(isset($expense))
                            <input name="month" type="hidden" value="{{ old('_token') ? old('month'): $expense->month }}">
                        @else
                            <input name="month" type="hidden" value="{{ old('_token') ? old('month'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a month</div>
                        <div class="menu">
                            @foreach(monthsDropDown() as $keyMonth => $valueMonth)
                                <div class="item" data-value="{{ $valueMonth }}">{{ $valueMonth }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ $errors->first('month') }}</p>
                </div>
            </div>

            <!-- staff field -->
            <!-- Salary, Salary Advance, Bonus, Loan -->
            <!-- Commission, Allowance, Fine, Transport -->
            <div class="col-md-3 staff-field" style="display: none;">
                <div class="form-group required {{ $errors->has('staff_id') ? 'has-danger' : '' }}">
                    <label class="control-label">Staff</label>
                    <div class="ui fluid search normal selection dropdown staff-drop-down {{ $errors->has('staff_id') ? 'error' : '' }}">
                        @if(isset($expense))
                            <input name="staff_id" type="hidden" value="{{ old('_token') ? old('staff_id'): $expense->staff_id }}">
                        @else
                            <input name="staff_id" type="hidden" value="{{ old('_token') ? old('staff_id'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a staff</div>
                        <div class="menu">
                            @foreach(staffsDropdown() as $keyStaff => $valueStaff)
                                <div class="item" data-value="{{ $keyStaff }}">{{ $valueStaff }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ $errors->first('staff_id') }}</p>
                </div>
            </div>

            <!-- installment period field -->
            <!-- Loan -->
            <div class="col-md-3 installment-period-field" style="display: none;">
                {!! form()->bsText('installment_period', 'Installment period', null, ['placeholder' => 'installment period']) !!}
            </div>

            <!-- no of days field -->
            <!-- Allowance -->
            <div class="col-md-3 days-field" style="display: none;">
                {!! form()->bsText('no_of_days', 'No of Days', null, ['placeholder' => 'no of days']) !!}
            </div>

            <!-- vehicle field -->
            <!-- Vehicle Repair, Fuel, Service, Parking, Vehicle Maintenance, Lease, Fine, transport -->
            <div class="col-md-3 vehicle-field" style="display: none;">
                <div class="form-group required {{ $errors->has('vehicle_id') ? 'has-danger' : '' }}">
                    <label class="control-label">Vehicle</label>
                    <div class="ui fluid action input">
                        <div class="ui fluid  search selection dropdown vehicle-drop-down {{ $errors->has('vehicle_id') ? 'error' : '' }}">
                            @if(isset($expense))
                                <input name="vehicle_id" type="hidden" value="{{ old('_token') ? old('vehicle_id'): $expense->vehicle_id }}">
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
                    </div>
                    <p class="form-control-feedback">{{ $errors->first('vehicle_id') }}</p>
                </div>
            </div>

            <!-- liter field -->
            <!-- Fuel -->
            <div class="col-md-3 liter-field" style="display: none;">
                {!! form()->bsText('liter', 'Liters', null, ['placeholder' => 'liters']) !!}
            </div>

            <!-- odometer field -->
            <!-- Fuel -->
            <div class="col-md-3 odometer-field" style="display: none;">
                {!! form()->bsText('odometer', 'ODO Meter reading', null, ['placeholder' => 'odo meter reading']) !!}
            </div>

            <!-- what was repaired field -->
            <!-- Vehicle Repair -->
            <div class="col-md-3 what-repaired-field" style="display: none;">
                {!! form()->bsText('what_was_repaired', 'What was repaired?', null, ['placeholder' => 'what was repaired?']) !!}
            </div>

            <!-- changed item field -->
            <!-- Vehicle Repair, Service, Machine Maintenance -->
            <div class="col-md-3 changed-item-field" style="display: none;">
                {!! form()->bsText('changed_item', 'Changed item', null, ['placeholder' => 'changed item']) !!}
            </div>

            <!-- supplier field -->
            <!-- Vehicle Repair, Machine Maintenance -->
            {{--<div class="col-md-3 supplier-field" style="display: none;">
                <div class="form-group required {{ $errors->has('supplier_id') ? 'has-danger' : '' }}">
                    <label class="control-label">Supplier</label>
                    <div class="ui fluid  search selection dropdown supplier-drop-down {{ $errors->has('supplier_id') ? 'error' : '' }}">
                        @if(isset($expense))
                            <input name="supplier_id" type="hidden" value="{{ old('_token') ? old('supplier_id'): $expense->supplier_id }}">
                        @else
                            <input name="supplier_id" type="hidden" value="{{ old('_token') ? old('supplier_id'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a supplier</div>
                        <div class="menu">
                            @foreach(supplierDropDown() as $key => $supplier)
                                <div class="item" data-value="{{ $key }}">{{ $supplier }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ $errors->first('supplier_id') }}</p>
                </div>
            </div>--}}

            <!-- expiry date field -->
            <!-- Vehicle & Machine Repair, Machine Maintenance -->
            <div class="col-md-3 expiry-date-field" style="display: none;">
                {!! form()->bsText('repair_expiry_date', 'Expiry date', null, ['placeholder' => 'expiry date', 'class' => 'form-control datepicker']) !!}
            </div>

            <!-- repairing shop field -->
            <!-- Vehicle & Machine Repair, Machine Maintenance -->
            <div class="col-md-3 repairing-shop-field" style="display: none;">
                {!! form()->bsText('repairing_shop', 'Repairing shop', null, ['placeholder' => 'repairing shop']) !!}
            </div>

            <!-- labour charge field -->
            <!-- Vehicle, Machine Repair, Service, Machine Maintenance -->
            <div class="col-md-3 labour-charge-field" style="display: none;">
                {!! form()->bsText('labour_charge', 'Labour charge', null, ['placeholder' => 'labour charge']) !!}
            </div>

            <!-- driver field -->
            <!-- Vehicle Repair, Service -->
            <div class="col-md-3 driver-field" style="display: none;">
                <div class="form-group required {{ $errors->has('driver_id') ? 'has-danger' : '' }}">
                    <label class="control-label">Driver</label>
                    <div class="ui fluid  search selection dropdown driver-drop-down {{ $errors->has('driver_id') ? 'error' : '' }}">
                        @if(isset($expense))
                            <input name="driver_id" type="hidden" value="{{ old('_token') ? old('driver_id'): $expense->driver_id }}">
                        @else
                            <input name="driver_id" type="hidden" value="{{ old('_token') ? old('driver_id'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a driver</div>
                        <div class="menu">
                            @foreach(driverDropDown() as $key => $driver)
                                <div class="item" data-value="{{ $key }}">{{ $driver }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ $errors->first('driver_id') }}</p>
                </div>
            </div>

            <!-- odo at repair field -->
            <!-- Vehicle Repair -->
            <div class="col-md-3 odo-at-repair-field" style="display: none;">
                {!! form()->bsText('odo_at_repair', 'ODO at repair', null, ['placeholder' => 'odo at repair']) !!}
            </div>

            <!-- service station field -->
            <!-- Service -->
            <div class="col-md-3 service-station-field" style="display: none;">
                {!! form()->bsText('service_station', 'Service station', null, ['placeholder' => 'service station']) !!}
            </div>

            <!-- odo at service field -->
            <!-- Service -->
            <div class="col-md-3 odo-at-service-field" style="display: none;">
                {!! form()->bsText('odo_at_service', 'ODO at service', null, ['placeholder' => 'odo at service']) !!}
            </div>

            <!-- parking name field -->
            <!-- Parking -->
            <div class="col-md-3 parking-name-field" style="display: none;">
                {!! form()->bsText('parking_name', 'Parking name', null, ['placeholder' => 'parking name']) !!}
            </div>

            <!-- vehicle maintenance type field -->
            <!-- Vehicle Maintenance type(Tax, Insurance, Fitness, Emission) -->
            <div class="col-md-3 vehicle-maintenance-type-field" style="display: none;">
                <div class="form-group required {{ $errors->has('vehicle_maintenance_type') ? 'has-danger' : '' }}">
                    <label class="control-label">Vehicle maintenance type</label>
                    <div class="ui fluid  search selection dropdown vehicle-main-type-drop-down {{ $errors->has('vehicle_maintenance_type') ? 'error' : '' }}">
                        @if(isset($expense))
                            <input name="vehicle_maintenance_type" type="hidden" value="{{ old('_token') ? old('vehicle_maintenance_type'): $expense->vehicle_maintenance_type }}">
                        @else
                            <input name="vehicle_maintenance_type" type="hidden" value="{{ old('_token') ? old('vehicle_maintenance_type'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a type</div>
                        <div class="menu">
                            <div class="item" data-value="Tax">Tax</div>
                            <div class="item" data-value="Insurance">Insurance</div>
                            <div class="item" data-value="Fitness">Fitness</div>
                            <div class="item" data-value="Emission">Emission</div>
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ $errors->first('vehicle_maintenance_type') }}</p>
                </div>
            </div>

            <!-- from date field -->
            <!-- Vehicle Maintenance, Income Tax, CEB, Telephone, Room Charge, Water -->
            <div class="col-md-3 from-date-field" style="display: none;">
                {!! form()->bsText('from_date', 'From date', null, ['placeholder' => 'from date', 'class' => 'form-control datepicker']) !!}
            </div>

            <!-- to date field -->
            <!-- Vehicle Maintenance, Income Tax, CEB, Telephone, Room Charge, Water -->
            <div class="col-md-3 to-date-field" style="display: none;">
                {!! form()->bsText('to_date', 'To date', null, ['placeholder' => 'to date', 'class' => 'form-control datepicker']) !!}
            </div>

            <!-- no of months field -->
            <!-- Lease -->
            <div class="col-md-3 no-of-months-field" style="display: none;">
                {!! form()->bsText('no_of_months', 'No of months', null, ['placeholder' => 'no of months']) !!}
            </div>

            <!-- fine reason field -->
            <!-- Fine -->
            <div class="col-md-3 fine-reason-field" style="display: none;">
                {!! form()->bsText('fine_reason', 'Fine reason', null, ['placeholder' => 'fine reason']) !!}
            </div>

            <!-- from destination field -->
            <!-- Transport -->
            <div class="col-md-3 from-destination-field" style="display: none;">
                {!! form()->bsText('from_destination', 'From destination', null, ['placeholder' => 'from destination']) !!}
            </div>

            <!-- to destination field -->
            <!-- Transport -->
            <div class="col-md-3 to-destination-field" style="display: none;">
                {!! form()->bsText('to_destination', 'TO destination', null, ['placeholder' => 'to destination']) !!}
            </div>

            <!-- no of bags field -->
            <!-- Transport -->
            <div class="col-md-3 no-of-bags-field" style="display: none;">
                {!! form()->bsText('no_of_bags', 'No of bags', null, ['placeholder' => 'no of bags']) !!}
            </div>

            <!-- account number field -->
            <!-- CEB, Telephone, Water -->
            <div class="col-md-3 account-number-field" style="display: none;">
                {!! form()->bsText('account_number', 'Account number', null, ['placeholder' => 'account number']) !!}
            </div>

            <!-- units reading field -->
            <!-- CEB, Water -->
            <div class="col-md-3 units-reading-field" style="display: none;">
                {!! form()->bsText('units_reading', 'Units reading', null, ['placeholder' => 'units reading']) !!}
            </div>

            <!-- machine field -->
            <!-- Machine Maintenance -->
            <div class="col-md-3 machine-field" style="display: none;">
                {!! form()->bsText('machine', 'Machine', null, ['placeholder' => 'machine']) !!}
            </div>

            <!-- festival name field -->
            <!-- Festival Expense -->
            <div class="col-md-3 festival-name-field" style="display: none;">
                {!! form()->bsText('festival_name', 'Festival name', null, ['placeholder' => 'festival name']) !!}
            </div>

            <!-- donated to field -->
            <!-- Donation -->
            <div class="col-md-3 donated-to-field" style="display: none;">
                {!! form()->bsText('donated_to', 'Donated to', null, ['placeholder' => 'donated to']) !!}
            </div>

            <!-- donated reason field -->
            <!-- Donation -->
            <div class="col-md-3 donated-reason-field" style="display: none;">
                {!! form()->bsText('donated_reason', 'Donated reason', null, ['placeholder' => 'donated reason']) !!}
            </div>

            <!-- hotel name field -->
            <!-- Room Charges -->
            <div class="col-md-3 hotel-name-field" style="display: none;">
                {!! form()->bsText('hotel_name', 'Hotel name', null, ['placeholder' => 'hotel name']) !!}
            </div>

            <!-- bank number field -->
            <!-- OD Interest, CHQ Book Issue -->
            <div class="col-md-3 bank-number-field" style="display: none;">
                {!! form()->bsText('bank_number', 'Bank number', null, ['placeholder' => 'bank number']) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="form-group {{ $errors->has('supplier_id') ? 'has-danger' : '' }}">
                <label class="control-label">Supplier</label>
                <div class="ui fluid  search selection dropdown supplier-drop-down {{ $errors->has('supplier_id') ? 'error' : '' }}">
                    @if(isset($expense))
                        <input name="supplier_id" type="hidden" value="{{ old('_token') ? old('supplier_id'): $expense->supplier_id }}">
                    @else
                        <input name="supplier_id" type="hidden" value="{{ old('_token') ? old('supplier_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a supplier</div>
                    <div class="menu">
                        @foreach(supplierDropDown() as $key => $supplier)
                            <div class="item" data-value="{{ $key }}">{{ $supplier }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('supplier_id') }}</p>
            </div>
        </div>
        @if(!isset($expense))
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Attach Receipts</label>
                    <input type="file" name="files[]" class="form-control" multiple id="fileUpload" aria-describedby="fileHelp">
                    <p class="form-control-feedback">{{ $errors->first('files') }}</p>
                </div>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-md-9">
            {!! form()->bsTextarea('notes', 'Expense narration', null, ['placeholder' => 'enter expense related narration here...', 'cols' => 100, 'rows' => 3]) !!}
        </div>
    </div>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    @parent
    @include('expense.receipt._inc.script')
@endsection