<div class="form-body">
    <div class="row">
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group required">
                        <label class="control-label">Is route active?</label>
                        <div class="demo-radio-button">
                            <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes" checked="">
                            <label for="Yes">Yes</label>
                            <input name="is_active" value="No" type="radio" class="with-gap" id="No">
                            <label for="No">No</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-12">
                    <div class="form-group required {{ $errors->has('company_id') ? 'has-danger' : '' }}">
                        <label class="control-label">Company</label>
                        <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('company_id') ? 'error' : '' }}">
                            @if(isset($route))
                                <input name="company_id" type="hidden" value="{{ old('_token') ? old('company_id'): $route->company_id }}">
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
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! form()->bsText('name', 'Name', null, ['placeholder' => 'enter route name']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    {!! form()->bsText('cl_amount', 'Credit limit (CL)', null, ['placeholder' => 'credit limit amount']) !!}
                </div>
                <div class="col-md-4">
                    {!! form()->bsText('cl_notify_rate', 'CL used notify at', null, ['placeholder' => 'CL used notify at']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter route related notes here...', 'rows' => '5'], false) !!}
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12" style="margin-bottom: 10px">
                    <button type="button" onclick="clearMap()"
                            class="btn waves-effect waves-light btn-info btn-sm pull-right">Reset map
                    </button>
                </div>
                <div class="col-md-12">
                    <div id="map"
                         style="height:355px;position:relative"></div>
                    <input type="hidden" name="start_point" id="startPoint">
                    <input type="hidden" name="end_point" id="endPoint">
                    <input type="hidden" name="way_points" id="wayPoints">
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
    @include('_inc.route-map.index', ['route' => isset($route) ? $route : null])
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        $('.selectpicker').selectpicker({
            style: 'form-control',
            size: 4
        });
        var dropDown = $('.drop-down');
        dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });
    </script>
@endsection