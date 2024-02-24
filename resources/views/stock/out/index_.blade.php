@extends('layouts.master')
@section('title', 'Stocks Out')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stocks Out') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h3 class="text-white">Enter stock out details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'stock.out.store', 'method' => 'POST']) }}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group required {{ ($errors->has('store_id')) ? 'has-danger' : '' }}">
                                    <label class="control-label">Store</label>
                                    <div class="ui fluid search normal selection dropdown drop-down">
                                        <input name="store_id" type="hidden" value="{{ old('_token') ? old('store_id'): '' }}">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a store</div>
                                        <div class="menu">
                                            @foreach(storeDropDown() as $key => $store)
                                                <div class="item" data-value="{{ $key }}">{{ $store }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ ($errors->has('store_id') ? $errors->first('store_id') : '') }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group required {{ ($errors->has('product_id')) ? 'has-danger' : '' }}">
                                    <label class="control-label">Product</label>
                                    <div class="ui fluid search normal selection dropdown drop-down">
                                        <input name="product_id" type="hidden" value="{{ old('_token') ? old('product_id'): '' }}">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a product</div>
                                        <div class="menu">
                                            @foreach(productDropDown() as $key => $product)
                                                <div class="item" data-value="{{ $key }}">{{ $product }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="form-control-feedback">{{ ($errors->has('product_id') ? $errors->first('product_id') : '') }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                {!! form()->bsText('qty', 'Out qty', null, ['placeholder' => 'enter out qty']) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter stock out related notes here...', 'rows' => '3'], false) !!}
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! form()->bsSubmit('Submit') !!}
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