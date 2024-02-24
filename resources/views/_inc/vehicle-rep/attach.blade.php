<div id="assign_vehicle_wizard" class="hidden">
    <hr>
    <form action="{{ route('setting.search.vehicle.rep.attach', [class_basename($model), $model, $relation]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="row">
            <div class="col-md-6">
                <div class="ui fluid search selection dropdown {{ ($errors->has($name)) ? 'error' : '' }}"
                     id="vehicle_drop_down">
                    <input type="hidden" name="{{$name}}">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose {{ $name }} to assign</div>
                    <div class="menu"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group  required">
                    <input class="form-control datepicker" placeholder="choose assigned date"
                           name="date" type="text" id="assigned_date" autocomplete="off">
                </div>
            </div>
            <div class="col-md-3">
                <button type="Submit" class="btn btn-success"><i class="fa fa-check"></i> Assign</button>
                <button type="button" class="btn btn-inverse" id="assign_vehicle_close_btn"><i
                            class="fa fa-remove"></i> Cancel</button>
            </div>
        </div>
    </form>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        $('.selectpicker').selectpicker({
            style: 'form-control',
            size: 4
        });
    </script>
    <script>
        $assignVehicleEl = {
            btn: $('#assign_vehicle'),
            wizard: $('#assign_vehicle_wizard'),
            dropDown: $('#vehicle_drop_down'),
            closeBtn: $('#assign_vehicle_close_btn')
        };

        $assignVehicleEl.btn.click(function (e) {
            e.preventDefault();
            $assignVehicleEl.wizard.removeClass('hidden');
            $('.datepicker').datepicker( "setDate", new Date() );
        });

        $assignVehicleEl.closeBtn.click(function (e) {
            e.preventDefault();
            $assignVehicleEl.wizard.addClass('hidden');
        });

        @if ($errors->has('staff'))
        $assignVehicleEl.wizard.removeClass('hidden');
        @endif

        $assignVehicleEl.dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: '{{ route('setting.vehicle.search') }}/{query}',
                cache: false
            }
        })

    </script>
@endsection