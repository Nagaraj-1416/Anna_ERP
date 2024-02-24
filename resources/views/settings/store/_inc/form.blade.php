<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Company</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    @if(isset($store))
                        <input name="company_id" type="hidden" value="{{ old('_token') ? old('company_id'): $store->company_id }}">
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
        <div class="col-md-3">
            {!! form()->bsText('name', 'Name', null, ['placeholder' => 'enter store name']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Store type</label>
                <div class="demo-radio-button">
                    <input name="type" value="General" type="radio" class="with-gap" id="General" checked="" {{ (old('type') == 'General' || (isset($store) && $store->type  == 'General')) ? 'checked' : ''}}>
                    <label for="General">General</label>
                    <input name="type" value="Damage" type="radio" class="with-gap" id="Damage" {{ (old('type') == 'Damage' || (isset($store) && $store->type  == 'No')) ? 'Damage' : ''}}>
                    <label for="Damage">Damage</label>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Is store active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes" checked="" {{ (old('is_active') == 'Yes' || (isset($store) && $store->is_active  == 'Yes')) ? 'checked' : ''}}>
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap" id="No" {{ (old('is_active') == 'No' || (isset($store) && $store->is_active  == 'No')) ? 'checked' : ''}}>
                    <label for="No">No</label>
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
            {!! form()->bsText('email', 'Email address', null, ['placeholder' => 'eg: example@gmail.com']) !!}
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
    </script>
@endsection