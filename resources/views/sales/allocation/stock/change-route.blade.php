@extends('layouts.master')
@section('title', 'Change Route')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Change Route of this Allocation</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($dailyStock, ['url' => route('daily.stock.do.change.route', $dailyStock), 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group required {{ $errors->has('route') ? 'has-danger' : '' }}">
                                    <label class="control-label">Route</label>
                                    <div class="ui fluid search selection dropdown route-drop-down {{ $errors->has('route') ? 'error' : '' }}">
                                        <input name="route" type="hidden" value="">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a route</div>
                                        <div class="menu">
                                            @foreach($routes as $key => $route)
                                                <div class="item" data-value="{{ $key }}">{{ $route }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('route') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! form()->bsSubmit('Submit', 'btn btn-success waves-effect waves-light m-r-10', 'Submit', 'submit') !!}
                    {!! form()->bsCancel('Cancel', 'daily.stock.show', [$dailyStock]) !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        var repDropDown = $('.route-drop-down');
        repDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });
    </script>
@endsection
