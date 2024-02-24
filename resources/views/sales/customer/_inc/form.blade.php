<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('route_id') ? 'has-danger' : '' }}">
                <label class="control-label">Route</label>
                <div class="ui fluid normal search selection dropdown route-drop-down {{ $errors->has('route_id') ? 'error' : '' }}">
                    @if(isset($customer))
                        <input name="route_id" type="hidden"
                               value="{{ old('_token') ? old('route_id'): $customer->route_id }}">
                    @else
                        <input name="route_id" type="hidden" value="{{ old('_token') ? old('route_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a route</div>
                    <div class="menu">
                        @foreach(routeDropDown() as $key => $route)
                            <div class="item" data-value="{{ $key }}">{{ $route }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('route_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('location_id') ? 'has-danger' : '' }}">
                <label class="control-label">Route location</label>
                <div class="ui fluid normal search selection dropdown location-drop-down {{ $errors->has('location_id') ? 'error' : '' }}">
                    @if(isset($customer))
                        <input name="location_id" type="hidden" value="{{ old('_token') ? old('location_id'): $customer->location_id }}">
                    @else
                        <input name="location_id" type="hidden" value="{{ old('_token') ? old('location_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a route location</div>
                </div>
                <p class="form-control-feedback">{{ $errors->first('location_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Is customer active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes"
                           checked="" {{ (old('is_active') == 'Yes' || (isset($customer) && $customer->is_active  == 'Yes')) ? 'checked' : ''}}>
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap"
                           id="No" {{ (old('is_active') == 'No' || (isset($customer) && $customer->is_active  == 'No')) ? 'checked' : ''}}>
                    <label for="No">No</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    {!! form()->bsText('cl_amount', 'Credit limit (CL)', null, ['placeholder' => 'credit limit amount']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    {!! form()->bsText('cl_notify_rate', 'CL used notify at', null, ['placeholder' => 'CL used notify at']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    {!! form()->bsText('cl_days', 'Allowed credit balance limit days', null, ['placeholder' => 'allowed days']) !!}
                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Customer logo</label>
                <input type="file" class="form-control" id="customerLogo" name="logo_file">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Salutation</label>
                <div class="ui fluid normal selection dropdown drop-down">
                    @if(isset($customer))
                        <input name="salutation" type="hidden"
                               value="{{ old('_token') ? old('salutation'): $customer->salutation }}">
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
            {!! form()->bsText('first_name', 'First name', null, ['placeholder' => 'enter first name']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('last_name', 'Last name', null, ['placeholder' => 'enter last name']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('display_name', 'Display name (In English)', null, ['placeholder' => 'enter display name (english)']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('tamil_name', 'Display name (In Tamil)', null, ['placeholder' => 'enter display name (tamil)']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('phone', 'Phone no', null, ['placeholder' => 'eg: 0215555551']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('fax', 'Fax no', null, ['placeholder' => 'eg: 0215555552']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('mobile', 'Mobile no', null, ['placeholder' => 'eg: 0775555553']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('email', 'Email address', null, ['placeholder' => 'eg: example@gmail.com']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('website', 'Website', null, ['placeholder' => 'https://samplesite.com']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter customer related notes here...', 'rows' => '4'], false) !!}
        </div>
    </div>
    <h4 class="box-title">Customer Address</h4>
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

        var route = $('.route-drop-down');
        var routeLocation = $('.location-drop-down');
        route.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            onChange: function (value) {
                locationDropDown(value);
            }
        });

        function locationDropDown(value) {
            routeLocation.dropdown('clear');
            var routeId = value;
            var url = '{{ route('setting.route.location.search', ['routeId']) }}';
            url = url.replace('routeId', routeId);
            routeLocation.dropdown('setting', {
                apiSettings: {
                    url: url + '/{query}',
                    cache: false,
                },
                saveRemoteData: false
            });
        }

        @if(isset($customer))
            @if(isset($customer->route))
                var customerId = '{{ $customer->route->id }}';
                route.dropdown('set value', '{{ $customer->route->id }}');
                route.dropdown('set text', '{{ $customer->route->name }}');
                locationDropDown(customerId);
            @endif
            @if(isset($customer->location))
                routeLocation.dropdown('set value', '{{ $customer->location->id }}');
                routeLocation.dropdown('set text', '{{ $customer->location->name }} ({{ $customer->location->code }})');
            @endif
        @endif
        @if(old('_token'))
            var old = @json(old());
            @if(old('route_id'))
                route.dropdown('set value', old.route_id);
                route.dropdown('set text',  old.route_name);
                locationDropDown(old.route_id);
            @endif
            @if(old('location_id'))
                routeLocation.dropdown('set value', old.location_id);
                routeLocation.dropdown('set text', old.location_name);
            @endif
        @endif
    </script>
@endsection
