<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('company_id') ? 'has-danger' : '' }}">
                <label class="control-label">Company</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('company_id') ? 'error' : '' }}">
                    @if(isset($productionUnit))
                        <input name="company_id" type="hidden" value="{{ old('_token') ? old('company_id'): $productionUnit->company_id }}">
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
                <p class="form-control-feedback">{{ $errors->first('company_id') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            {!! form()->bsText('name', 'Name', null, ['placeholder' => 'enter production unit name']) !!}
        </div>
        <div class="col-md-6">
            <div class="form-group required">
                <label class="control-label">Is production unit active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes" checked="" {{ (old('is_active') == 'Yes' || (isset($productionUnit) && $productionUnit->is_active  == 'Yes')) ? 'checked' : ''}}>
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap" id="No" {{ (old('is_active') == 'No' || (isset($productionUnit) && $productionUnit->is_active  == 'No')) ? 'checked' : ''}}>
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
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter production unit related notes here...', 'rows' => '5']) !!}
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