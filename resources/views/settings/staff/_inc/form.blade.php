<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Staff profile image</label>
                <input type="file" class="form-control" name="staff_image">
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group required {{ ($errors->has('is_active')) ? 'has-danger' : '' }}">
                <label class="control-label">Is staff active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes"
                           checked="" {{ (old('is_active') == 'Yes' || (isset($staff) && $staff->is_active  == 'Yes')) ? 'checked' : ''}}>
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap"
                           id="No" {{ (old('is_active') == 'No' || (isset($staff) && $staff->is_active == 'No'))  ? 'checked' : ''}}>
                    <label for="No">No</label>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Salutation</label>
                <div class="ui fluid normal selection dropdown drop-down">
                    @if(isset($staff))
                        <input name="salutation" type="hidden"
                               value="{{ old('_token') ? old('salutation'): $staff->salutation }}">
                    @else
                        <input name="salutation" type="hidden" value="{{ old('_token') ? old('salutation'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a salutation</div>
                    <div class="menu">
                        @foreach(salutationDropDown() as $key => $salutation)
                            <div class="item" data-value="{{ $key }}">{{ $salutation }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            {!! form()->bsText('first_name', 'First name', null, ['placeholder' => 'enter staff first name']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('last_name', 'Last name', null, ['placeholder' => 'enter staff last name']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('short_name', 'Short name', null, ['placeholder' => 'enter staff short name']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('phone', 'Phone no', null, ['placeholder' => 'eg: 0215555551'], false) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('mobile', 'Mobile no', null, ['placeholder' => 'eg: 0215555552']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('email', 'Email address', null, ['placeholder' => 'eg: example@gmail.com']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ ($errors->has('gender')) ? 'has-danger' : '' }}">
                <label class="control-label">Gender</label>
                <div class="demo-radio-button">
                    <input name="gender" value="Male" type="radio" class="with-gap"
                           id="gender_male" {{ (old('gender') == 'Male' || (isset($staff) && $staff->gender == 'Male')) ? 'checked' : ''}}>
                    <label for="gender_male">Male</label>
                    <input name="gender" value="Female" type="radio" class="with-gap"
                           id="gender_female" {{ (old('gender') == 'Female' || (isset($staff) && $staff->gender == 'Female')) ? 'checked' : ''}}>
                    <label for="gender_female">Female</label>
                </div>
                <p class="form-control-feedback">{{ ($errors->has('gender') ? $errors->first('gender') : '') }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('expense_category') ? 'has-danger' : '' }}">
                <label class="control-label">Designation</label>
                <div class="ui fluid action input">
                    <div class="ui fluid  search selection dropdown designation-drop-down {{ $errors->has('expense_category') ? 'error' : '' }}">
                        <input type="hidden" name="designation_id">
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a designation</div>
                        <div class="menu"></div>
                    </div>
                    <button type="button" class="ui blue right icon button" id="designation-drop-down-add-btn">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
                <p class="form-control-feedback">{{ $errors->first('designation') }}</p>
            </div>
        </div>
        {{--<div class="col-md-3">--}}
        {{--{!! form()->bsText('designation', 'Designation', null, ['placeholder' => 'eg: Sales Executive', 'class' => 'form-control']) !!}--}}
        {{--</div>--}}
        <div class="col-md-3">
            {!! form()->bsText('joined_date', 'Joined date', null, ['placeholder' => 'eg: 2005-11-23', 'class' => 'form-control datepicker']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('resigned_date', 'Resigned date', null, ['placeholder' => 'eg: 2017-11-23', 'class' => 'form-control datepicker'], false) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('dob', 'Date of birth', null, ['placeholder' => 'eg: 1988-11-20', 'class' => 'form-control datepicker']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter staff related notes here...', 'rows' => '5'], false) !!}
        </div>
    </div>
    <h4 class="box-title">Finance Details</h4>
    <hr>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('bank_name', 'Bank name', null, ['placeholder' => 'enter bank name'], false) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('branch', 'Branch', null, ['placeholder' => 'enter bank branch'], false) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('account_name', 'Account name', null, ['placeholder' => 'enter account name', 'class' => 'form-control'], false) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('account_no', 'Account no', null, ['placeholder' => 'enter account no', 'class' => 'form-control'], false) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('epf_no', 'EPF No', null, ['placeholder' => 'enter epf no'], false) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('etf_no', 'ETF No', null, ['placeholder' => 'enter etf no'], false) !!}
        </div>
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has('pay_rate')) ? 'has-danger' : '' }}">
                <label class="control-label">Pay Rate</label>
                <div class="demo-radio-button">
                    <input name="pay_rate" value="Monthly" type="radio" class="with-gap"
                           id="pay_rate_monthly" {{ (old('pay_rate') == 'Monthly' || (isset($staff) && $staff->pay_rate == 'Monthly')) ? 'checked' : ''}}>
                    <label for="pay_rate_monthly">Monthly</label>
                    <input name="pay_rate" value="Weekly" type="radio" class="with-gap"
                           id="pay_rate_weekly" {{ (old('pay_rate') == 'Weekly' || (isset($staff) && $staff->pay_rate == 'Weekly')) ? 'checked' : ''}}>
                    <label for="pay_rate_weekly">Weekly</label>
                    <input name="pay_rate" value="Hourly" type="radio" class="with-gap"
                           id="pay_rate_hourly" {{ (old('pay_rate') == 'Hourly' || (isset($staff) && $staff->pay_rate == 'Hourly')) ? 'checked' : ''}}>
                    <label for="pay_rate_hourly">Hourly</label>
                </div>
                <p class="form-control-feedback">{{ ($errors->has('pay_rate') ? $errors->first('pay_rate') : '') }}</p>
            </div>
        </div>
    </div>
    <div class="sales-details" style="margin-bottom: 25px;">
        <h4 class="box-title">Sales Details</h4>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group required {{ ($errors->has('is_sales_rep')) ? 'has-danger' : '' }}">
                    <label class="control-label">Is this staff a sales rep?</label>
                    <div class="demo-radio-button">
                        <input name="is_sales_rep" value="Yes" type="radio" class="with-gap is_sales_rep"
                               id="sales_rep_Yes" {{ (old('is_sales_rep') == 'Yes' || (isset($staff) && $staff->is_sales_rep  == 'Yes' )) ? 'checked' : ''}}>
                        <label for="sales_rep_Yes">Yes</label>
                        <input name="is_sales_rep" value="No" type="radio" class="with-gap is_sales_rep"
                               id="sales_rep_No" {{ (old('is_sales_rep') == 'No' || (isset($staff) && $staff->is_sales_rep == 'No')) ? 'checked' : ''}}>
                        <label for="sales_rep_No">No</label>
                    </div>
                    <p class="form-control-feedback">{{ ($errors->has('is_sales_rep') ? $errors->first('is_sales_rep') : '') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group required vehicle-drop-down {{ ($errors->has('vehicle_id')) ? 'has-danger' : '' }}">
                    <label class="control-label">Associate a vehicle</label>
                    <div class="ui fluid normal search selection dropdown drop-down {{ $errors->has('vehicle_id') ? 'error' : '' }}">
                        @if(isset($staff) && $staff->rep)
                            <input name="vehicle_id" type="hidden"
                                   value="{{ old('_token') ? old('vehicle_id'): $staff->rep->vehicle_id }}">
                        @else
                            <input name="vehicle_id" type="hidden" value="{{ old('_token') ? old('vehicle_id'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a vehicle to associate</div>
                        <div class="menu">
                            @foreach(vehicleDropDown() as $key => $vehicle)
                                <div class="item" data-value="{{ $key }}">{{ $vehicle }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ ($errors->has('vehicle_id') ? $errors->first('vehicle_id') : '') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group required route-drop-down {{ ($errors->has('route_id')) ? 'has-danger' : '' }}">
                    <label class="control-label">Associate a sales route</label>
                    <div class="ui fluid normal search selection dropdown drop-down {{ $errors->has('route_id') ? 'error' : '' }}">
                        @if(isset($staff) && $staff->rep)
                            <input name="route_id" type="hidden"
                                   value="{{ old('_token') ? old('route_id'): $staff->rep->vehicle_id }}">
                        @else
                            <input name="route_id" type="hidden" value="{{ old('_token') ? old('route_id'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a route to associate</div>
                        <div class="menu">
                            @foreach(routeDropDown() as $key => $route)
                                <div class="item" data-value="{{ $key }}">{{ $route }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ ($errors->has('route_id') ? $errors->first('route_id') : '') }}</p>
                </div>
            </div>
            <div class="col-md-3 cl-details-panel">
                <div class="row">
                    <div class="col-md-7">
                        {!! form()->bsText('cl_amount', 'Credit limit (CL)', isset($staff->rep) && $staff->rep ? $staff->rep->cl_amount : null, ['placeholder' => 'credit limit amount']) !!}
                    </div>
                    <div class="col-md-5">
                        {!! form()->bsText('cl_notify_rate', 'CL used notify at', isset($staff->rep) && $staff->rep ? $staff->rep->cl_notify_rate : null, ['placeholder' => 'CL used notify at']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="login_details">
        <h4 class="box-title">Login Details</h4>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group required {{ ($errors->has('create_user')) ? 'has-danger' : '' }}">
                    <label class="control-label">Is this staff a system user?</label>
                    <div class="demo-radio-button">
                        <input name="create_user" value="Yes" type="radio" class="with-gap create_user"
                               id="user_yes" {{ (old('create_user') == 'Yes' || (isset($staff) && $staff->user)) ? 'checked' : ''}}>
                        <label for="user_yes">Yes</label>
                        <input name="create_user" value="No" type="radio" class="with-gap create_user"
                               id="user_no" {{ (old('create_user') == 'No' || (isset($staff) && !$staff->user)) ? 'checked' : ''}}>
                        <label for="user_no">No</label>
                    </div>
                    <p class="form-control-feedback">{{ ($errors->has('create_user') ? $errors->first('create_user') : '') }}</p>
                </div>
            </div>
        </div>
        <div class="row role_selection">
            <div class="col-md-3">
                <div class="form-group required {{ ($errors->has('role')) ? 'has-danger' : '' }}">
                    <label class="control-label">Role</label>
                    <div class="ui fluid normal selection dropdown drop-down {{ $errors->has('role') ? 'error' : '' }}">
                        @if(isset($staff) && $staff->user)
                            <input name="role" type="hidden"
                                   value="{{ old('_token') ? old('role'): $staff->user->role_id }}">
                        @else
                            <input name="role" type="hidden" value="{{ old('_token') ? old('role'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a role</div>
                        <div class="menu">
                            @foreach(roleDropDown() as $key => $role)
                                <div class="item" data-value="{{ $key }}">{{ $role }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ ($errors->has('role') ? $errors->first('role') : '') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group required {{ ($errors->has('password')) ? 'has-danger' : '' }}">
                    <label class="control-label">Password</label>
                    {{ form()->password('password',  ['class' => 'form-control', 'placeholder' => 'enter password']) }}
                    <p class="form-control-feedback">{{ ($errors->has('password') ? $errors->first('password') : '') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group required {{ ($errors->has('password_confirmation')) ? 'has-danger' : '' }}">
                    <label class="control-label">Confirm password</label>
                    {{ form()->password('password_confirmation',  ['class' => 'form-control', 'placeholder' => 'confirm password']) }}
                    <p class="form-control-feedback">{{ ($errors->has('password_confirmation') ? $errors->first('password_confirmation') : '') }}</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group required {{ ($errors->has('prefix_first') || $errors->has('prefix_last') || $errors->has('prefix')) ? 'has-danger' : ''}}">
                    {{ form()->label('prefix') }}
                    <div class="input-group">
                        <input type="text" class="form-control "
                               maxlength="3" minlength="2"
                               name="prefix_first"
                               value="{{ old('_token') ? old('prefix_first') : (isset($staff) ? $staff->prefix_first : '') }}">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                /
                            </span>
                        </div>
                        <input type="text" class="form-control"
                               maxlength="3" minlength="2" name="prefix_last"
                               value="{{ old('_token') ? old('prefix_last') : (isset($staff) ? $staff->prefix_last : '')  }}">
                    </div>
                    <p class="form-control-feedback">{{ ($errors->has('prefix_last') ? $errors->first('prefix_last') : '') }}</p>
                </div>
            </div>
        </div>
        <div class="row role_selection" style="margin-bottom: 25px;">
            <div class="col-md-12">
                <div class="form-group required {{ ($errors->has('allowed_non_working_hrs')) ? 'has-danger' : '' }}">
                    <label class="control-label">Allowed non-working hours?</label>
                    <div class="demo-radio-button">
                        <input name="allowed_non_working_hrs" value="Yes" type="radio" class="with-gap" id="NonWorkingHrsYes"
                               checked="" {{ (old('allowed_non_working_hrs') == 'Yes' || (isset($staff->user) && $staff->user->allowed_non_working_hrs  == 'Yes')) ? 'checked' : ''}}>
                        <label for="NonWorkingHrsYes">Yes</label>
                        <input name="allowed_non_working_hrs" value="No" type="radio" class="with-gap" id="NonWorkingHrsNo"
                               {{ (old('allowed_non_working_hrs') == 'No' || (isset($staff->user) && $staff->user->allowed_non_working_hrs == 'No'))  ? 'checked' : ''}}>
                        <label for="NonWorkingHrsNo">No</label>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="row role_selection" style="margin-bottom: 25px;">
            <div class="col-md-12">
                <div class="form-group required {{ ($errors->has('business_type')) ? 'has-danger' : '' }}">
                    <label class="control-label">Accessible Business Types</label>
                    <div class="ui fluid search normal selection dropdown multiple search business-type drop-down {{ $errors->has('business_type') ? 'error' : '' }}">
                        @if(isset($associatedTypes))
                            <input name="business_type" type="hidden"
                                   value="{{ old('_token') ? old('business_type'): $associatedTypes }}">
                        @else
                            <input name="business_type" type="hidden"
                                   value="{{ old('_token') ? old('business_type'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose related business types</div>
                        <div class="menu">
                            @foreach(businessTypeDropDown() as $key => $businessType)
                                <div class="item" data-value="{{ $key }}">{{ $businessType }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ ($errors->has('business_type') ? $errors->first('business_type') : '') }}</p>
                </div>
            </div>
        </div>--}}
    </div>
    <h4 class="box-title">Staff Address</h4>
    <hr>
    @include('_inc.address.form')
    <input type="hidden" name="business_type" value="1">
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@include('settings.staff._inc.add')
@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        var dropDown = $('.drop-down');
        dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        $('.designation-drop-down').dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: '{{ route('setting.designation.search') }}' + '/{query}',
                cache: false
            }
        });

        $formEl = {
            create_user: $('.create_user'),
            role_selection: $('.role_selection'),
            is_sales_rep: $('.is_sales_rep'),
            vehicle_drop_down: $('.vehicle-drop-down'),
            route_drop_down: $('.route-drop-down'),
            clDetailsPanel: $('.cl-details-panel')
        };
        @if ((isset($staff) && $staff->user) || old('create_user') == 'Yes')
        $formEl.role_selection.fadeIn();
        @else
        $formEl.role_selection.fadeOut();
        @endif

        @if ((isset($staff) && $staff->rep) || old('is_sales_rep') == 'Yes')
        $formEl.vehicle_drop_down.fadeIn();
        $formEl.route_drop_down.fadeIn();
        $formEl.clDetailsPanel.fadeIn();
        @else
        $formEl.vehicle_drop_down.fadeOut();
        $formEl.route_drop_down.fadeOut();
        $formEl.clDetailsPanel.fadeOut();
        @endif

        $formEl.create_user.change(function (e) {
            e.preventDefault();
            if ($(this).val() === 'Yes') {
                $formEl.role_selection.fadeIn();
            } else {
                $formEl.role_selection.fadeOut();
            }
        });

        $formEl.is_sales_rep.change(function (e) {
            e.preventDefault();
            if ($(this).val() === 'Yes') {
                $formEl.vehicle_drop_down.fadeIn();
                $formEl.route_drop_down.fadeIn();
                $formEl.clDetailsPanel.fadeIn();
            } else {
                $formEl.vehicle_drop_down.fadeOut();
                $formEl.route_drop_down.fadeOut();
                $formEl.clDetailsPanel.fadeOut();
            }
        });

        @if(old('_token') && old('designation'))
            let values = @json(old('designation'));
            setValue(values);
        @endif
        @if(!old('_token') && isset($staff))
            let values = @json($staff->designation);
            setValue(values);
        @endif
        function setValue(values) {
            $('.designation-drop-down').dropdown('set text', values.name).dropdown('set value', values.id);
        }
    </script>
@endsection