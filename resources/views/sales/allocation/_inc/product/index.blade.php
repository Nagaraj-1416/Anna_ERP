<div id="products-sidebar" class="card card-outline-info disabled-dev" ng-controller="AddProductController" style="border: none !important;">
    <div class="cus-create-preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
        </svg>
    </div>
    <div class="card-header ">
        <h3 class="m-b-0 text-white">Products</h3>
        <h6 class="card-subtitle text-white">Add products to allocation</h6>
    </div>
    <div class="card-body" id="add-cus-body" style="overflow-y: scroll; height: 350px;">
        <div class="form">
            <div class="form-body">
                <div class="alert alert-danger" ng-show="errors.hasOwnProperty('unauthorized')">
                    <h5 class="text-danger">
                        <i class="fa fa-exclamation-circle"></i> This action is unauthorized.
                    </h5>
                </div>
                <div class="row cheque-data">
                    <div class="col-md-12">
                        <div class="form-group required" ng-class="hasError('products') ? 'has-danger' : ''">
                            <label for="products" class="control-label form-control-label">Products</label>
                            <div class="ui fluid search selection dropdown products-dropdown">
                                <input type="hidden" name="products">
                                <i class="dropdown icon"></i>
                                <div class="default text">choose products</div>
                                <div class="menu"></div>
                            </div>
                            <p class="form-control-feedback">@{{ getErrorMsg('products') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row product-data ">
                    <div class="col-md-12">
                        <hr>
                    </div>
                    <div class="col-md-12" ng-repeat="(key, product) in products" ng-show="products.length"
                         product-directive>
                        <div class="row">
                            <div class="col-md-12">
                                @{{ product.name }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group required">
                                    <div class="ui fluid  search selection dropdown store-drop-down"
                                         ng-class="hasError('products.'+key+'.store_id') ? 'error' : ''"
                                         data-index="@{{ product.id }}">
                                        <input type="hidden" name="product[store][@{{ product.id }}]"
                                               ng-class="hasError('store', product.id) ? 'error' : ''">
                                        <i class="dropdown icon"></i>
                                        <div class="default text">choose a store</div>
                                        <div class="menu">
                                            @foreach(storeDropDown() as $key => $store)
                                                <div class="item" data-value="{{ $key }}">{{ $store }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="help-block error"
                                       ng-show="getErrorMsg('products.'+key+'.store_id')">
                                        @{{ getErrorMsg('products.'+key+'.store_id') }}
                                    </p>
                                </div>
                                <p class="help-block error">@{{ getError('product.store.'+product.id) }}</p>
                            </div>
                            <div class="col-md-5">
                                <div>
                                    <input type="text" class="form-control quantity-text"
                                           ng-class="(hasError('products.'+key+'.default_qty')) ? 'error' : ''"
                                           name="product[quantity][@{{ product.id }}]"
                                           value="@{{ product.default_qty }}"
                                           ng-model="products[key].default_qty">
                                    <p class="help-block error"
                                       ng-show="getErrorMsg('products.'+key+'.default_qty')">
                                        @{{ getErrorMsg('products.'+key+'.default_qty') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="Button" class="btn btn-danger remove_row_btn"
                                        ng-click="removeProduct(key)">
                                    <i class="fa fa-remove"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" ng-show="!products.length">
                        <p class="text-danger">No products are selected</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                        <button type="button"
                                class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar"
                                data-ng-click="submitForm($event)">
                            <i class="fa fa-check"></i>
                            Submit
                        </button>
                        <button type="button" class="btn btn-inverse waves-effect waves-light"
                                data-ng-click="closeSideBar($event)">
                            <i class="fa fa-remove"></i> Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('style')
    @parent()
    <style>
        .error {
            color: red;
        }
    </style>
@endsection