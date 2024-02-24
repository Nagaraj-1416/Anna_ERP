<div class="form-body">
    <div class="row">
        <div class="col-md-7">
            {!! form()->bsText('name', 'Name', null, ['placeholder' => 'enter business type name']) !!}
        </div>
        <div class="col-md-5">
            <div class="form-group required">
                <label class="control-label">Is business type active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes" checked="" {{ (old('is_active') == 'Yes' || (isset($businessType) && $businessType->is_active  == 'Yes')) ? 'checked' : ''}}>
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap" id="No" {{ (old('is_active') == 'No' || (isset($businessType) && $businessType->is_active  == 'No')) ? 'checked' : ''}}>
                    <label for="No">No</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter business type related notes here...', 'rows' => '5'], false) !!}
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
@endsection