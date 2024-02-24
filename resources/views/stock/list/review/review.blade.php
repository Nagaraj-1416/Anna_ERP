@extends('layouts.master')
@section('title', 'review Stock')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h3 class="text-white">Review Stock</h3>
                </div>
                <div class="card-body">
                    {!! form()->model($stock, ['url' => route('stock.store.review', $stock), 'method' => 'POST']) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group required {{ $errors->has('staff_id') ? 'has-danger' : '' }}">
                                        <label class="control-label">Store staff</label>
                                        <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('staff_id') ? 'error' : '' }}">
                                            <input name="staff_id" type="hidden" value="{{ old('_token') ? old('staff_id'): '' }}">
                                            <i class="dropdown icon"></i>
                                            <div class="default text">choose a store staff</div>
                                            <div class="menu">
                                                @foreach($staff as $staffKey => $staffValue)
                                                    <div class="item" data-value="{{ $staffKey }}">{{ $staffValue }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="form-control-feedback">{{ $errors->first('staff_id') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group  required">
                                        <label for="available_stock" class="control-label form-control-label">Available Stock</label>
                                        <input class="form-control" placeholder="available stock" name="available_stock" type="text" value="{{ $stock->available_stock }}" id="available_stock">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    {!! form()->bsText('actual_qty', 'Actual Qty', null, ['placeholder' => 'enter actual qty']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter review related notes...', 'rows' => '3'], false) !!}
                                </div>
                            </div>
                        </div>
                        <hr>
                        {!! form()->bsSubmit('Add') !!}
                        {!! form()->bsCancel('Cancel', 'stock.index') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
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
