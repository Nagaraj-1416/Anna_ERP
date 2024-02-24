<div class="row">
    <div class="col-md-6">
        {!! form()->bsText('street_one', 'Street one', null, ['placeholder' => 'enter street one address']) !!}
    </div>
    <div class="col-md-6">
        {!! form()->bsText('street_two', 'Street two', null, ['placeholder' => 'enter street two address if available'], false) !!}
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        {!! form()->bsText('city', 'City', null, ['placeholder' => 'enter city']) !!}
    </div>
    <div class="col-md-3">
        {!! form()->bsText('province', 'Province', null, ['placeholder' => 'enter province']) !!}
    </div>
    <div class="col-md-3">
        {!! form()->bsText('postal_code', 'Postal code', null, ['placeholder' => 'enter postal code']) !!}
    </div>
    <div class="col-md-3">
        <div class="form-group required {{ $errors->has('country_id') ? 'has-danger' : '' }}">
            <label class="control-label">Country</label>
            <div class="ui fluid search normal selection dropdown country-drop-down {{ $errors->has('country_id') ? 'error' : '' }}">
                @if(isset($address))
                    <input name="country_id" type="hidden" value="{{ old('_token') ? old('country_id'): $address->country_id }}">
                @else
                    <input name="country_id" type="hidden" value="{{ old('_token') ? old('country_id'): 41 }}">
                @endif
                <i class="dropdown icon"></i>
                <div class="default text">choose a country</div>
                <div class="menu">
                    @foreach(countryDropDown() as $key => $country)
                        <div class="item" data-value="{{ $key }}">{{ $country }}</div>
                    @endforeach
                </div>
            </div>
            <p class="form-control-feedback">{{ $errors->first('country_id') }}</p>
        </div>
    </div>
</div>
@section('script')
    @parent
    <script>
        var countryDropDown = $('.country-drop-down');
        countryDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });
    </script>
@endsection