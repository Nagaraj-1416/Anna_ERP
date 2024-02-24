<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('name', 'Name', null, ['placeholder' => 'enter vehicle type name']) !!}
        </div>
        <div class="col-md-9">
            <div class="form-group required">
                <label class="control-label">Is vehicle type active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes" checked="">
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap" id="No">
                    <label for="No">No</label>
                </div>
            </div>
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