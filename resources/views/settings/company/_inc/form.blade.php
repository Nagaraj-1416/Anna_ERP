<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Company logo</label>
                <input type="file" class="form-control" id="companyLogo" name="logo_file">
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group required">
                <label class="control-label">Is company active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes" checked="" {{ (old('is_active') == 'Yes' || (isset($company) && $company->is_active  == 'Yes')) ? 'checked' : ''}}>
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap" id="No" {{ (old('is_active') == 'No' || (isset($company) && $company->is_active  == 'No')) ? 'checked' : ''}}>
                    <label for="No">No</label>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            {!! form()->bsText('name', 'Name', null, ['placeholder' => 'enter company name']) !!}
        </div>
        <div class="col-md-6">
            {!! form()->bsText('display_name', 'Display name', null, ['placeholder' => 'enter company display name']) !!}
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
            {!! form()->bsText('email', 'Email address', null, ['placeholder' => 'eg: example@gmail.com']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            {!! form()->bsText('website', 'Website', null, ['placeholder' => 'https://samplesite.com']) !!}
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('business_location', 'Business location', null, ['placeholder' => 'eg: Jaffna']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('base_currency', 'Base currency', null, ['placeholder' => 'eg: LKR']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('fy_starts_month') ? 'has-danger' : '' }}">
                <label class="control-label">Fiscal year starts month</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('fy_starts_month') ? 'error' : '' }}">
                    @if(isset($company))
                        <input name="fy_starts_month" type="hidden" value="{{ old('_token') ? old('fy_starts_month'): $company->fy_starts_month }}">
                    @else
                        <input name="fy_starts_month" type="hidden" value="{{ old('_token') ? old('fy_starts_month'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a starts month</div>
                    <div class="menu">
                        @foreach(monthsDropDown() as $key => $month)
                            <div class="item" data-value="{{ $key }}">{{ $month }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('fy_starts_month') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Fiscal year starts from</label>
                <div class="demo-radio-button">
                    <input name="fy_starts_from" value="Start" type="radio" class="with-gap" id="Start" checked="" {{ (old('fy_starts_from') == 'Start' || (isset($company) && $company->fy_starts_from  == 'Start')) ? 'checked' : ''}}>
                    <label for="Start">Start</label>
                    <input name="fy_starts_from" value="End" type="radio" class="with-gap" id="End" {{ (old('fy_starts_from') == 'End' || (isset($company) && $company->fy_starts_from  == 'End')) ? 'checked' : ''}}>
                    <label for="End">End</label>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('business_starts_at', 'Business starts at', null, ['placeholder' => 'choose business starts at time', 'class' => 'form-control clockpicker']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('business_end_at', 'Business ends at', null, ['placeholder' => 'choose business ends at time', 'class' => 'form-control clockpicker']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('date_time_format') ? 'has-danger' : '' }}">
                <label class="control-label">Date time format</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('date_time_format') ? 'error' : '' }}">
                    @if(isset($company))
                        <input name="date_time_format" type="hidden" value="{{ old('_token') ? old('date_time_format'): $company->date_time_format }}">
                    @else
                        <input name="date_time_format" type="hidden" value="{{ old('_token') ? old('date_time_format'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a date time format</div>
                    <div class="menu">
                        @foreach(dateTimeFormatDropDown() as $key => $dateTimeFormat)
                            <div class="item" data-value="{{ $key }}">{{ $dateTimeFormat }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('date_time_format') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            {!! form()->bsText('timezone', 'Timezone', null, ['placeholder' => 'timezone']) !!}
        </div>
    </div>
    <h4 class="box-title">Company Address</h4>
    <hr>
    @include('_inc.address.form')
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