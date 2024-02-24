<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Supplier logo</label>
                <input type="file" class="form-control" id="supplierLogo" name="logo_file">
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group required">
                <label class="control-label">Is supplier active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes" checked="" {{ (old('is_active') == 'Yes' || (isset($supplier) && $supplier->is_active  == 'Yes')) ? 'checked' : ''}}>
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap" id="No" {{ (old('is_active') == 'No' || (isset($supplier) && $supplier->is_active  == 'No')) ? 'checked' : ''}}>
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
                    @if(isset($supplier))
                        <input name="salutation" type="hidden" value="{{ old('_token') ? old('salutation'): $supplier->salutation }}">
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
        <div class="col-md-3">
            {!! form()->bsText('display_name', 'Display name', null, ['placeholder' => 'enter display name']) !!}
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
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter supplier related notes here...', 'rows' => '5'], false) !!}
        </div>
    </div>
    <h4 class="box-title">Supplier Address</h4>
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