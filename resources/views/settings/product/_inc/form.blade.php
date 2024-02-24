<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Product image</label>
                <input type="file" class="form-control" id="companyLogo" name="product_image">
            </div>
        </div>
        <div class="col-md-3">
            {!! form()->bsText('barcode_number', 'Barcode', null, ['placeholder' => 'barcode'], false) !!}
        </div>
        <div class="col-md-6">
            <div class="form-group required {{ ($errors->has('type')) ? 'has-danger' : '' }}">
                <label class="control-label">Product type</label>
                <div class="demo-radio-button">
                    <input name="type" value="Raw Material" type="radio" class="with-gap product-type"
                           id="pro_rm" {{ (old('type') == 'Raw Material' || (isset($product) && $product->type  == 'Raw Material')) ? 'checked' : ''}}>
                    <label for="pro_rm">Raw Material</label>
                    <input name="type" value="Finished Good" type="radio" class="with-gap product-type"
                           id="pro_fg" {{ (old('type') == 'Finished Good' || (isset($product) && $product->type  == 'Finished Good')) ? 'checked' : ''}}>
                    <label for="pro_fg">Finished Good</label>
                    <input name="type" value="Third Party Product" type="radio" class="with-gap product-type"
                           id="pro_tpp" {{ (old('type') == 'Third Party Product' || (isset($product) && $product->type  == 'Third Party Product')) ? 'checked' : ''}}>
                    <label for="pro_tpp">Third Party Product</label>
                </div>
                <p class="form-control-feedback">{{ ($errors->has('type') ? $errors->first('type') : '') }}</p>
            </div>
        </div>
        <div class="col-md-3" style="display: none;">
            <div class="form-group required">
                <label class="control-label">Is product active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes"
                           checked="" {{ (old('is_active') == 'Yes' || (isset($product) && $product->is_active  == 'Yes')) ? 'checked' : ''}}>
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap"
                           id="No" {{ (old('is_active') == 'No' || (isset($product) && $product->is_active  == 'No')) ? 'checked' : ''}}>
                    <label for="No">No</label>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('name', 'Name (In English)', null, ['placeholder' => 'enter product name (english)']) !!}
        </div>
        <div class="col-md-3">
            {!! form()->bsText('tamil_name', 'Name (In Tamil)', null, ['placeholder' => 'enter product name (tamil)']) !!}
        </div>
        <div class="col-md-3">
            <div class="form-group required {{ ($errors->has('category')) ? 'has-danger' : '' }}">
                <label class="control-label">Category</label>
                <div class="ui fluid search normal selection dropdown drop-down category-dropdown">
                    @if(isset($product))
                        <input name="category" type="hidden"
                               value="{{ old('_token') ? old('category') : $product->category->name ?? '' }}">
                    @else
                        <input name="category" type="hidden" value="{{ old('_token') ? old('category'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose or create</div>
                    <div class="menu">
                        @foreach(productCategoryDropDown() as $key => $category)
                            <div class="item" data-value="{{ $category }}">{{ $category }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ ($errors->has('category') ? $errors->first('category') : '') }}</p>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ ($errors->has('measurement')) ? 'has-danger' : '' }}">
                <label class="control-label">Measurement</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    @if(isset($product))
                        <input name="measurement" type="hidden"
                               value="{{ old('_token') ? old('measurement'): $product->measurement }}">
                    @else
                        <input name="measurement" type="hidden" value="{{ old('_token') ? old('measurement'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose a measurement</div>
                    <div class="menu">
                        @foreach(measurementDropDown() as $key => $measurement)
                            <div class="item" data-value="{{ $key }}">{{ $measurement }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ ($errors->has('measurement') ? $errors->first('measurement') : '') }}</p>
            </div>
        </div>
        <div class="col-md-3">
            {!! form()->bsText('min_stock_level', 'Min stock level', null, ['placeholder' => 'enter min stock level']) !!}
        </div>
        {{--<div class="col-md-3">
            <div class="form-group required {{ ($errors->has('inventory_account')) ? 'has-danger' : '' }}">
                <label class="control-label">Inventory account</label>
                <div class="ui fluid search normal selection dropdown drop-down">
                    @if(isset($product))
                        <input name="inventory_account" type="hidden"
                               value="{{ old('_token') ? old('inventory_account'): $product->inventory_account }}">
                    @else
                        <input name="inventory_account" type="hidden"
                               value="{{ old('_token') ? old('inventory_account'): '' }}">
                    @endif
                    <i class="dropdown icon"></i>
                    <div class="default text">choose an account</div>
                    <div class="menu">
                        @foreach(inventoryAccDropDown() as $key => $account)
                            <div class="item" data-value="{{ $key }}">{{ $account }}</div>
                        @endforeach
                    </div>
                </div>
                <p class="form-control-feedback">{{ ($errors->has('inventory_account') ? $errors->first('inventory_account') : '') }}</p>
            </div>
        </div>--}}
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Is product expirable?</label>
                <div class="demo-radio-button">
                    <input name="is_expirable" value="Yes" type="radio" class="with-gap" id="expirable-yes"
                           checked="" {{ (old('is_expirable') == 'Yes' || (isset($product) && $product->is_expirable  == 'Yes')) ? 'checked' : ''}}>
                    <label for="expirable-yes">Yes</label>
                    <input name="is_expirable" value="No" type="radio" class="with-gap"
                           id="expirable-no" {{ (old('is_expirable') == 'No' || (isset($product) && $product->is_expirable  == 'No')) ? 'checked' : ''}}>
                    <label for="expirable-no">No</label>
                </div>
            </div>
        </div>
    </div>

    <div class="sales-info" style="display: none;">
        <h4 class="box-title">Sales Details</h4>
        <hr>
        <div class="row">
            <div class="col-md-3">
                {!! form()->bsText('wholesale_price', 'Base wholesale price', null, ['placeholder' => 'enter base wholesale price']) !!}
            </div>
            <div class="col-md-3">
                {!! form()->bsText('retail_price', 'Base retail price', null, ['placeholder' => 'enter base retail price']) !!}
            </div>
            <div class="col-md-3">
                {!! form()->bsText('distribution_price', 'Base distribution price', null, ['placeholder' => 'enter base distribution price']) !!}
            </div>
            <div class="col-md-3">
                {!! form()->bsText('packet_price', 'Packet price', null, ['placeholder' => 'enter packet price']) !!}
            </div>
            {{--<div class="col-md-3">
                <div class="form-group required {{ ($errors->has('income_account')) ? 'has-danger' : '' }}">
                    <label class="control-label">Income account</label>
                    <div class="ui fluid search normal selection dropdown drop-down">
                        @if(isset($product))
                            <input name="income_account" type="hidden"
                                   value="{{ old('_token') ? old('income_account'): $product->income_account }}">
                        @else
                            <input name="income_account" type="hidden"
                                   value="{{ old('_token') ? old('income_account'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose an account</div>
                        <div class="menu">
                            @foreach(incomeAccDropDown() as $key => $account)
                                <div class="item" data-value="{{ $key }}">{{ $account }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ ($errors->has('income_account') ? $errors->first('income_account') : '') }}</p>
                </div>
            </div>--}}
        </div>
    </div>

    <div class="purchase-info" style="display: none;">
        <h4 class="box-title">Purchase Details</h4>
        <hr>
        <div class="row">
            <div class="col-md-3">
                {!! form()->bsText('buying_price', 'Base buying price', null, ['placeholder' => 'enter base buying price']) !!}
            </div>
            {{--<div class="col-md-3">
                <div class="form-group required {{ ($errors->has('expense_account')) ? 'has-danger' : '' }}">
                    <label class="control-label">Expense account</label>
                    <div class="ui fluid search normal selection dropdown drop-down">
                        @if(isset($product))
                            <input name="expense_account" type="hidden"
                                   value="{{ old('_token') ? old('expense_account'): $product->expense_account }}">
                        @else
                            <input name="expense_account" type="hidden"
                                   value="{{ old('_token') ? old('expense_account'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose an account</div>
                        <div class="menu">
                            @foreach(expenseAccDropDown() as $key => $account)
                                <div class="item" data-value="{{ $key }}">{{ $account }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ ($errors->has('expense_account') ? $errors->first('expense_account') : '') }}</p>
                </div>
            </div>--}}
        </div>
    </div>

    <div>
        <h4 class="box-title">Opening Details</h4>
        <hr>
        <div class="row">
            <div class="col-md-3">
                {!! form()->bsText('opening_cost', 'Opening cost', null, ['placeholder' => 'enter opening cost']) !!}
            </div>
            <div class="col-md-3">
                {!! form()->bsText('opening_cost_at', 'Opening cost at', null, ['placeholder' => 'enter opening cost at', 'class' => 'form-control datepicker']) !!}
            </div>
            <div class="col-md-3">
                {!! form()->bsText('opening_qty', 'Opening QTY', null, ['placeholder' => 'enter opening qty']) !!}
            </div>
            <div class="col-md-3">
                {!! form()->bsText('opening_qty_at', 'Opening QTY at', null, ['placeholder' => 'enter opening qty at', 'class' => 'form-control datepicker']) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter product related notes here...', 'rows' => '5']) !!}
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

        $productFormEl = {
            productType: $('.product-type'),
            salesInfo: $('.sales-info'),
            purchaseInfo: $('.purchase-info')
        };

        $productFormEl.productType.change(function (e) {
            e.preventDefault();
            if ($(this).val() === 'Raw Material') {
                $productFormEl.salesInfo.hide();
                $productFormEl.purchaseInfo.show();
            } else if ($(this).val() === 'Finished Good') {
                $productFormEl.purchaseInfo.hide();
                $productFormEl.salesInfo.show();
            } else if ($(this).val() === 'Third Party Product') {
                $productFormEl.purchaseInfo.show();
                $productFormEl.salesInfo.show();
            }
        });
        var type = '';
        @if(old('_token'))
            type = '{{ old('type') }}';
        @elseif(isset($product))
            type = '{{ $product->type }}';
        @endif

        if (type === 'Raw Material'){
            $productFormEl.salesInfo.hide();
            $productFormEl.purchaseInfo.show();
        }else if(type === 'Finished Good'){
            $productFormEl.purchaseInfo.hide();
            $productFormEl.salesInfo.show();
        }else if(type === 'Third Party Product'){
            $productFormEl.purchaseInfo.show();
            $productFormEl.salesInfo.show();
        }

        $('.category-dropdown').dropdown('setting', {
            allowAdditions: true
        }).on('keyup', function () {
            var text = $(this).find('input[class="search"]').val();
            $('.category-dropdown').dropdown('set value', text);
        });

    </script>
@endsection