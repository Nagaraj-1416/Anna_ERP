<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Company</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    @if(isset($salesLocation))
                        <input name="company_id" type="hidden" value="{{ old('_token') ? old('company_id'): $salesLocation->company_id }}">
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
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required">
                <label class="control-label">Is sales location active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes" checked="" {{ (old('is_active') == 'Yes' || (isset($salesLocation) && $salesLocation->is_active  == 'Yes')) ? 'checked' : ''}}>
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap" id="No" {{ (old('is_active') == 'No' || (isset($salesLocation) && $salesLocation->is_active  == 'No')) ? 'checked' : ''}}>
                    <label for="No">No</label>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('name', 'Name', null, ['placeholder' => 'enter sales location name']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Sales location type</label>
                <div class="demo-radio-button">
                    <input name="type" value="Shop" type="radio" class="with-gap location-type" id="Shop" checked="" {{ (old('type') == 'Shop' || (isset($salesLocation) && $salesLocation->type  == 'Shop')) ? 'checked' : ''}}>
                    <label for="Shop">Shop</label>
                    <input name="type" value="Sales Van" type="radio" class="with-gap location-type" id="Sales Van" {{ (old('type') == 'Sales Van' || (isset($salesLocation) && $salesLocation->type  == 'Sales Van')) ? 'checked' : ''}}>
                    <label for="Sales Van">Sales Van</label>
                    <input name="type" value="Other" type="radio" class="with-gap location-type" id="Other" {{ (old('type') == 'Other' || (isset($salesLocation) && $salesLocation->type  == 'Other')) ? 'checked' : ''}}>
                    <label for="Other">Other</label>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required vehicle-drop-down-panel" style="display: none;">
                <label class="control-label">Vehicle</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    @if(isset($salesLocation))
                        <input name="vehicle_id" type="hidden" value="{{ old('_token') ? old('vehicle_id'): $salesLocation->vehicle_id }}">
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
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('phone', 'Phone no', null, ['placeholder' => 'eg: 0215555551']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('fax', 'Fax no', null, ['placeholder' => 'eg: 0215555552']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('mobile', 'Mobile no', null, ['placeholder' => 'eg: 0775555553']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('email', 'Email address', null, ['placeholder' => 'eg: example@gmail.com'], false) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter store related notes here...', 'rows' => '5']) !!}
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

        $locationFormEl = {
            locationType: $('.location-type'),
            vehicleDropDownPanel: $('.vehicle-drop-down-panel')
        };

        $locationFormEl.locationType.change(function (e) {
            e.preventDefault();
            if ($(this).val() === 'Sales Van') {
                $locationFormEl.vehicleDropDownPanel.show();
            } else {
                $locationFormEl.vehicleDropDownPanel.hide();
            }
        });

        @if(old('_token'))
            @if(old('type') == 'Sales Van')
                $locationFormEl.vehicleDropDownPanel.show();
            @else
                $locationFormEl.vehicleDropDownPanel.hide();
            @endif
        @endif

        var typeOnLoad = $locationFormEl.locationType.val();
        if (typeOnLoad === 'Sales Van') {
            $locationFormEl.vehicleDropDownPanel.show();
        } else {
            $locationFormEl.vehicleDropDownPanel.hide();
        }
    </script>
@endsection