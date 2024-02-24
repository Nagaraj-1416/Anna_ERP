<div id="assign_form" class="hidden">
    <hr>
    <form action="{{ $actionUrl }}" method="POST">
        {!! csrf_field() !!}
        <div class="row">
            <div class="col-md-9">
                <div class="ui fluid multiple search selection dropdown {{ ($errors->has('models')) ? 'error' : '' }}"
                     id="model_drop_down">
                    <input type="hidden" name="models">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a {{ $placeholderName }} to assign</div>
                    <div class="menu"></div>
                </div>
            </div>
            <div class="col-md-3">
                <button type="Submit" class="btn btn-success"><i class="fa fa-check"></i> Assign</button>
                <button type="button" class="btn btn-inverse" id="model_close_btn"><i class="fa fa-remove"></i> Cancel</button>
            </div>
        </div>
    </form>
</div>
@section('script')
    @parent
    <script>
        $assignStaffEl = {
            btn: $('#assign_model_btn'),
            wizard: $('#assign_form'),
            dropDown: $('#model_drop_down'),
            closeBtn: $('#model_close_btn')
        };

        $assignStaffEl.btn.click(function (e) {
            e.preventDefault();
            $assignStaffEl.wizard.removeClass('hidden');
        });

        $assignStaffEl.closeBtn.click(function (e) {
            e.preventDefault();
            $assignStaffEl.wizard.addClass('hidden');
        });

        @if ($errors->has('staff'))
            $assignStaffEl.wizard.removeClass('hidden');
        @endif

        $assignStaffEl.dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: '{{ $searchUrl }}/{query}',
                cache: false
            }
        })
    </script>
@endsection