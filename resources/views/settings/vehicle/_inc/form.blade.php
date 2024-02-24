<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Vehicle image</label>
                <input type="file" class="form-control" name="image">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required">
                <label class="control-label">Is vehicle active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes"
                           checked="" {{ (old('is_active') == 'Yes' || (isset($vehicle) && $vehicle->is_active  == 'Yes')) ? 'checked' : ''}}>
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap"
                           id="No" {{ (old('is_active') == 'No' || (isset($vehicle) && $vehicle->is_active  == 'No')) ? 'checked' : ''}}>
                    <label for="No">No</label>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ ($errors->has('company_id')) ? 'has-danger' : '' }}">
                <label class="control-label">Company</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ ($errors->has('company_id')) ? 'error' : '' }}">
                    @if(isset($vehicle))
                        <input name="company_id" type="hidden"
                               value="{{ old('_token') ? old('company_id'): $vehicle->company_id }}">
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
                <p class="form-control-feedback">{{ ($errors->has('company_id') ? $errors->first('company_id') : '') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            {!! form()->bsText('reg_date', 'Register date', null, ['placeholder' => 'enter vehicle registration date', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('vehicle_no', 'Vehicle no', null, ['placeholder' => 'enter vehicle no']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('engine_no', 'Engine no', null, ['placeholder' => 'enter vehicle engine no']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('chassis_no', 'Chassis no', null, ['placeholder' => 'enter vehicle chassis no']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ ($errors->has('type_id')) ? 'has-danger' : '' }}">
                <label class="control-label">Vehicle type</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ ($errors->has('type_id')) ? 'error' : '' }}">
                    @if(isset($vehicle))
                        <input name="type_id" type="hidden"
                               value="{{ old('_token') ? old('type_id'): $vehicle->type_id }}">
                    @else
                        <input name="type_id" type="hidden" value="{{ old('_token') ? old('type_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a vehicle type</div>
                    <div class="menu">
                        @foreach(vehicleTypeDropDown() as $key => $type)
                            <div class="item" data-value="{{ $key }}">{{ $type }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ ($errors->has('type_id') ? $errors->first('type_id') : '') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ ($errors->has('make_id')) ? 'has-danger' : '' }}">
                <label class="control-label">Vehicle make</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ ($errors->has('make_id')) ? 'error' : '' }}">
                    @if(isset($vehicle))
                        <input name="make_id" type="hidden"
                               value="{{ old('_token') ? old('make_id'): $vehicle->make_id }}">
                    @else
                        <input name="make_id" type="hidden" value="{{ old('_token') ? old('make_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a vehicle make</div>
                    <div class="menu">
                        @foreach(makeDropDown() as $key => $make)
                            <div class="item" data-value="{{ $key }}">{{ $make }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ ($errors->has('make_id') ? $errors->first('make_id') : '') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ ($errors->has('model_id')) ? 'has-danger' : '' }}">
                <label class="control-label">Vehicle model</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ ($errors->has('model_id')) ? 'error' : '' }}">
                    @if(isset($vehicle))
                        <input name="model_id" type="hidden"
                               value="{{ old('_token') ? old('model_id'): $vehicle->model_id }}">
                    @else
                        <input name="model_id" type="hidden" value="{{ old('_token') ? old('model_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a vehicle model</div>
                    <div class="menu">
                        @foreach(vehicleModelDropDown() as $key => $model)
                            <div class="item" data-value="{{ $key }}">{{ $model }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ ($errors->has('model_id') ? $errors->first('model_id') : '') }}</p>
            </div>
        </div>
    </div>
    <div class="row m-b-10">
        <div class="col-md-3">
            {!! form()->bsText('year', 'Year', null, ['placeholder' => 'enter vehicle year', 'class' => 'form-control yearpicker']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('color', 'Color', null, ['placeholder' => 'enter vehicle color']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Fuel type?</label>
                <div class="demo-radio-button">
                    <input name="fuel_type" value="Petrol" type="radio" class="with-gap" id="Petrol"
                           checked="" {{ (old('fuel_type') == 'Petrol' || (isset($vehicle) && $vehicle->fuel_type  == 'Petrol')) ? 'checked' : ''}}>
                    <label for="Petrol">Petrol</label>
                    <input name="fuel_type" value="Diesel" type="radio" class="with-gap"
                           id="Diesel" {{ (old('fuel_type') == 'Diesel' || (isset($vehicle) && $vehicle->fuel_type  == 'Diesel')) ? 'checked' : ''}}>
                    <label for="Diesel">Diesel</label>
                    <input name="fuel_type" value="Electric" type="radio" class="with-gap"
                           id="Electric" {{ (old('fuel_type') == 'Electric' || (isset($vehicle) && $vehicle->fuel_type  == 'Electric')) ? 'checked' : ''}}>
                    <label for="Electric">Electric</label>
                    <input name="fuel_type" value="Other" type="radio" class="with-gap"
                           id="Other" {{ (old('fuel_type') == 'Other' || (isset($vehicle) && $vehicle->fuel_type  == 'Other')) ? 'checked' : ''}}>
                    <label for="Other">Other</label>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Category?</label>
                <div class="demo-radio-button">
                    <input name="category" value="General" type="radio" class="with-gap"
                           id="General"
                           checked="" {{ (old('category') == 'General' || (isset($vehicle) && $vehicle->category  == 'General')) ? 'checked' : ''}}>
                    <label for="General">General</label>
                    <input name="category" value="Sales" type="radio" class="with-gap"
                           id="Sales" {{ (old('category') == 'Sales' || (isset($vehicle) && $vehicle->category  == 'Sales')) ? 'checked' : ''}}>
                    <label for="Sales">Sales</label>
                </div>
            </div>
        </div>
    </div>
    <h4 class="box-title">Vehicle Specifications</h4>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="row m-t-10">
                <div class="col-md-3">
                    {!! form()->bsText('type_of_body', 'Body type', null, ['placeholder' => 'enter vehicle body type', 'class' => 'form-control'], false) !!}
                </div>
                <div class="col-md-3">
                    {!! form()->bsText('seating_capacity', 'Seating capacity', null, ['placeholder' => 'enter vehicle seating capacity', 'class' => 'form-control'], false) !!}
                </div>
                <div class="col-md-3">
                    {!! form()->bsText('weight', 'Weight -KG', null, ['placeholder' => 'enter vehicle weight in KG', 'class' => 'form-control'], false) !!}
                </div>
                <div class="col-md-3">
                    {!! form()->bsText('gross', 'Gross -KG', null, ['placeholder' => 'enter vehicle gross in KG', 'class' => 'form-control'], false) !!}
                </div>
                <div class="col-md-3">
                    {!! form()->bsText('length', 'Length', null, ['placeholder' => 'enter vehicle length', 'class' => 'form-control'], false) !!}
                </div>
                <div class="col-md-3">
                    {!! form()->bsText('width', 'Width', null, ['placeholder' => 'enter vehicle width', 'class' => 'form-control'], false) !!}
                </div>
                <div class="col-md-3">
                    {!! form()->bsText('height', 'Height', null, ['placeholder' => 'enter vehicle height', 'class' => 'form-control'], false) !!}
                </div>
                <div class="col-md-3">
                    {!! form()->bsText('tyre_size_front', 'Front tyre size ', null, ['placeholder' => 'enter vehicle front tyre size', 'class' => 'form-control'], false) !!}
                </div>
                <div class="col-md-3">
                    {!! form()->bsText('tyre_size_rear', 'Rear tyre size', null, ['placeholder' => 'enter vehicle rear tyre size', 'class' => 'form-control'], false) !!}
                </div>
                <div class="col-md-3">
                    {!! form()->bsText('wheel_front', 'Front wheel', null, ['placeholder' => 'enter vehicle front wheel count', 'class' => 'form-control'], false) !!}
                </div>
                <div class="col-md-3">
                    {!! form()->bsText('wheel_rear', 'Rear wheel', null, ['placeholder' => 'enter vehicle rear wheel count', 'class' => 'form-control'], false) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter vehicle related notes here...', 'rows' => '3'], false) !!}
        </div>
    </div>
</div>

@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
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