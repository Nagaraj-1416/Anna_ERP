@extends('layouts.master')
@section('title', 'Change Rep')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Change Rep of this Allocation</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($dailyStock, ['url' => route('daily.stock.do.change.rep', $dailyStock), 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group required {{ $errors->has('rep') ? 'has-danger' : '' }}">
                                    <label class="control-label">Rep</label>
                                    <div class="ui fluid search selection dropdown rep-drop-down {{ $errors->has('rep') ? 'error' : '' }}">
                                        <input name="rep" type="hidden" value="">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a rep</div>
                                        <div class="menu">
                                            @foreach($reps as $key => $rep)
                                                <div class="item" data-value="{{ $key }}">{{ $rep }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ $errors->first('rep') }}</p>
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
        var repDropDown = $('.rep-drop-down');
        repDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });
    </script>
@endsection
