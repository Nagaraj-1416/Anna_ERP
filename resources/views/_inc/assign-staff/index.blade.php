<div id="assign_staff_wizard" class="hidden">
    <hr>
    <form action="{{ $actionURL }}" method="POST">
        {!! csrf_field() !!}
        <div class="row">
            <div class="col-md-9">
                <div class="ui fluid multiple search selection dropdown {{ ($errors->has('staff')) ? 'error' : '' }}"
                     id="staff_drop_down">
                    <input type="hidden" name="staff">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose staff to assign</div>
                    <div class="menu"></div>
                </div>
            </div>
            <div class="col-md-3">
                <button type="Submit" class="btn btn-success"><i class="fa fa-check"></i> Assign</button>
                <button type="button" class="btn btn-inverse" id="assign_staff_close_btn"><i class="fa fa-remove"></i> Cancel</button>
            </div>
        </div>
    </form>
    <hr>
</div>

@section('script')
    @parent
    <script>
        $assignStaffEl = {
            btn: $('#assign_staff_btn'),
            wizard: $('#assign_staff_wizard'),
            dropDown: $('#staff_drop_down'),
            closeBtn: $('#assign_staff_close_btn')
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
                url: '{{ $searchURL }}/{query}',
                cache: false
            }
        })
    </script>
@endsection