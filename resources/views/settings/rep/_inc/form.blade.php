<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('name', 'Name', null, ['placeholder' => 'enter rep name']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Staff</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    @if(isset($rep))
                        <input name="staff_id" type="hidden" value="{{ old('_token') ? old('staff_id'): $rep->staff_id }}">
                    @else
                        <input name="staff_id" type="hidden" value="{{ old('_token') ? old('staff_id'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a staff</div>
                    <div class="menu">
                        @foreach(staffsDropdown() as $key => $rep)
                            <div class="item" data-value="{{ $key }}">{{ $rep }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required">
                <label class="control-label">Is rep active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes" checked="">
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap" id="No">
                    <label for="No">No</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter route related notes here...', 'rows' => '5'], false) !!}
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