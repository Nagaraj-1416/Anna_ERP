<div class="form-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required {{ $errors->has('company_id') ? 'has-danger' : '' }}">
                <label class="control-label">Company</label>
                <div class="ui fluid search normal selection dropdown drop-down {{ $errors->has('company_id') ? 'error' : '' }}">
                    @if(isset($priceBook))
                        <input name="company_id" type="hidden"
                               value="{{ old('_token') ? old('company_id'): $priceBook->company_id }}">
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
                @if ($errors->has('company_id'))
                    <p class="form-control-feedback">
                        {{ $errors->first('company_id') }}
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Is price book active?</label>
                <div class="demo-radio-button">
                    <input name="is_active" value="Yes" type="radio" class="with-gap" id="Yes"
                           checked="" {{ (old('is_active') == 'Yes' || (isset($priceBook) && $priceBook->is_active  == 'Yes')) ? 'checked' : ''}}>
                    <label for="Yes">Yes</label>
                    <input name="is_active" value="No" type="radio" class="with-gap"
                           id="No" {{ (old('is_active') == 'No' || (isset($priceBook) && $priceBook->is_active  == 'No')) ? 'checked' : ''}}>
                    <label for="No">No</label>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            {!! form()->bsText('name', 'Name', null, ['placeholder' => 'enter price book name']) !!}
        </div>
        <div class="col-md-9">
            <div class="form-group required">
                <label class="control-label">Price book category</label>
                <div class="demo-radio-button">
                    <input name="category" value="Production To Store" type="radio" class="with-gap"
                           id="ProductionToStore"
                           checked="" {{ (old('category') == 'Production To Store' || (isset($priceBook) && $priceBook->category  == 'Production To Store')) ? 'checked' : ''}}>
                    <label for="ProductionToStore">Production To Store</label>
                    <input name="category" value="Store To Store" type="radio" class="with-gap"
                           id="StoreToStore" {{ (old('category') == 'Store To Store' || (isset($priceBook) && $priceBook->category  == 'Store To Store')) ? 'checked' : ''}}>
                    <label for="StoreToStore">Store To Store</label>
                    <input name="category" value="Store To Shop" type="radio" class="with-gap"
                           id="StoreToShop" {{ (old('category') == 'Store To Shop' || (isset($priceBook) && $priceBook->category  == 'Store To Shop')) ? 'checked' : ''}}>
                    <label for="StoreToShop">Store To Shop</label>
                    <input name="category" value="Shop Selling Price" type="radio" class="with-gap"
                           id="ShopSellingPrice" {{ (old('category') == 'Shop Selling Price' || (isset($priceBook) && $priceBook->category  == 'Shop Selling Price')) ? 'checked' : ''}}>
                    <label for="ShopSellingPrice">Shop Selling Price</label>
                    <input name="category" value="Van Selling Price" type="radio" class="with-gap"
                           id="VanSellingPrice" {{ (old('category') == 'Van Selling Price' || (isset($priceBook) && $priceBook->category  == 'Van Selling Price')) ? 'checked' : ''}}>
                    <label for="VanSellingPrice">Van Selling Price</label>
                    <input name="category" value="Virtual Price" type="radio" class="with-gap"
                           id="VirtualPrice" {{ (old('category') == 'Virtual Price' || (isset($priceBook) && $priceBook->category  == 'Virtual Price')) ? 'checked' : ''}}>
                    <label for="VirtualPrice">Virtual Price</label>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group required  {{ $errors->has('related_to') ? 'has-danger' : '' }}">
                <label class="control-label">Related to</label>
                <div class="ui fluid  search selection dropdown related-drop-down form-control {{ $errors->has('related_to') ? 'error' : '' }}" name="related_to">
                    <input type="hidden" name="related_to">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose an item</div>
                    <div class="menu"></div>
                </div>
                @if ($errors->has('related_to'))
                    <p class="form-control-feedback">
                        {{ $errors->first('related_to') }}
                    </p>
                @endif
            </div>
        </div>
        {{--<div class="col-md-3">
            <div class="form-group required">
                <label class="control-label">Price book type</label>
                <div class="demo-radio-button">
                    <input name="type" value="Selling Price" type="radio" class="with-gap" id="selling-price"
                           checked="" {{ (old('type') == 'Selling Price' || (isset($priceBook) && $priceBook->type  == 'Selling Price')) ? 'checked' : ''}}>
                    <label for="selling-price">Selling Price</label>
                    <input name="type" value="Buying Price" type="radio" class="with-gap"
                           id="buying-price" {{ (old('type') == 'Buying Price' || (isset($priceBook) && $priceBook->type  == 'Buying Price')) ? 'checked' : ''}}>
                    <label for="buying-price">Buying Price</label>
                </div>
            </div>
        </div>--}}
    </div>

    <h5 class="box-title box-title-with-margin">Price Book Line Items </h5>
    <hr>
    <div class="table-responsive price-book-line-items" ng-controller="PriceController"
         style="overflow-x: visible !important;">
        <table class="table color-table inverse-table po-table">
            <thead>
            <tr>
                <th>Product</th>
                <th style="width: 20%;">Range starts from</th>
                <th style="width: 12%;">Range ends to</th>
                <th style="width: 12%;">Price</th>
                <th style="width: 5%;"></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-show="prices.length">
               <td colspan="5">
                   <input type="text" style="margin-left: 0 !important;"
                          ng-model="productSearch" placeholder="type your keywords here and search for products"
                          class="form-control"
                          autocomplete="off">
               </td>
            </tr>
            <tr ng-repeat="(index , price) in prices | filter:productSearch" product-loop>
                @include('settings.price-book._inc.price-template')
            </tr>
            <tr class="item-btn-container">
                <td colspan="6">
                    <button type="button" class="btn btn-info btn-sm" ng-click="addNewPrice()"><i
                                class="fa fa-plus"></i> Add Another Item
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            {!! form()->bsTextarea('notes', 'Notes', null, ['placeholder' => 'enter price book related notes here...', 'rows' => '5'], false) !!}
        </div>
    </div>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
    <style>
        .has-danger .form-control {
            border: 1px solid red !important;
        }

        .has-danger .ui.selection.dropdown.form-control {
            border: 1px solid red !important;
        }
    </style>
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    <script>
        var dropDown = $('.drop-down');
        var relaredToDropdown = $('.related-drop-down');

        var relatedTo = '{{ route('setting.related.to.search', ['related' => 'RELATED']) }}';

        dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        $('input[name="category"]').change(function () {
            relaredToDropdown.dropdown('clear');
            initRelatedToDropDown($(this).val());
        });



        function initRelatedToDropDown(relateToModal) {
            relaredToDropdown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: relatedTo.replace('RELATED', relateToModal) + '/{query}',
                    cache: false
                }
            });
        };

        function setDropDownValue(dd, value, name) {
            dd.dropdown("refresh");
            dd.dropdown('set value', value);
            dd.dropdown('set text', name);
        };

        // Set old values
        var FormOldData = @json(old());
        @if (old('_token'))
            setDropDownValue(relaredToDropdown, FormOldData.related_to , FormOldData.related_to_name);
        @elseif(isset($priceBook) && $priceBook->relatedTo)
            setDropDownValue(relaredToDropdown, '{{ $priceBook->relatedTo->id }}', "{{ $priceBook->relatedTo->name }}");
        @endif

        @if (old('_token'))
            var category = FormOldData.category;
        @else
            var category = '{{ isset($priceBook) ? $priceBook->category : 'Production To Store' }}';
        @endif

        initRelatedToDropDown(category);
    </script>

    @include('settings.price-book._inc.price-script')
@endsection