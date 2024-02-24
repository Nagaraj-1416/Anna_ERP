<div ng-controller="DailyStockProductsController">
    @if(isset($visible) && $visible)
        <div class="card border-purple">
            <div class="card-body">
                <div class="d-flex no-block">
                    <h3 class="card-title text-purple">Stock Allocations</h3>
                    <div class="ml-auto"></div>
                </div>
                <h6 class="card-subtitle">as at {{ carbon()->now()->format('F j, Y') }}</h6>
                <hr>
                <table id="demo-foo-addrow" class="table m-t-10 table-hover no-wrap contact-list"
                       data-page-size="10">
                    <thead>
                    <tr>
                        <th style="width: 25%;">To allocate on</th>
                        <th>Vehicle & rep details</th>
                        <th>Route & product details</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="@{{ getStatusColor(dailyStock.status) }}" ng-repeat="dailyStock in dailyStocks"
                        ng-show="dailyStocks.length">
                        <td>
                            <a target="_blank" href="/sales/daily-stock/@{{ dailyStock.id }}">@{{ dailyStock.date }}</a> <br/>
                            <small ng-if="dailyStock.pre_al_code">Previous allocation:
                                <a target="_blank" href="/sales/allocation/@{{ dailyStock.pre_allocation_id }}"> @{{
                                    dailyStock.pre_al_code }} | @{{ dailyStock.pre_al_date }}</a>
                            </small>
                        </td>
                        <td>
                            @{{ dailyStock.sale_location.name }}<br/>
                            <small class="text-muted-double">@{{ dailyStock.rep.name }}</small>
                        </td>
                        <td>
                            @{{ dailyStock.route.name }}<br/>
                            <small class="text-muted-double">
                                <a href="" ng-click="listDailyStockData(dailyStock)">Required products: @{{
                                    dailyStock.no_of_products }}</a>
                            </small>
                        </td>
                        <td>
                            <span ng-class="dailyStock.status == 'Pending' ? 'text-warning' : ''  ||
                                dailyStock.status == 'Allocated' ? 'text-green' : '' ||
                                dailyStock.status == 'Canceled' ? 'text-danger' : ''">@{{ dailyStock.status }}</span><br/>
                            <small class="text-muted-double">
                                Prepared by: @{{ dailyStock.prepared_staff }}
                            </small>
                        </td>
                    </tr>
                    <tr ng-show="!dailyStocks.length">
                        <td colspan="6">No data to display...</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <div id="daily-stock-sidebar" class="card card-outline-inverse disabled-dev" style="border: none !important;">
        <div class="expense-cat-create-preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
            </svg>
        </div>
        <div class="card-header ">
            <h3 class="m-b-0 text-white">Stock allocation summary</h3>
            <h6 class="card-subtitle text-white">Require list of products</h6>
            <h6 class="card-subtitle text-white">Issued Products -: @{{ getIssuedProduct() }}</h6>
        </div>
        <div class="card-body" id="daily-stock">
            {{--<div class="form">--}}
            {{--<div class="form-body">--}}
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover contact-list table-scroll">
                        <thead>
                        <tr>
                            <th class="">Product</th>
                            <th width="15%" class="text-center">Available Qty in Store</th>
                            <th width="15%" class="text-center">Default qty</th>
                            <th width="15%" class="text-center">Available qty</th>
                            <th width="15%" class="text-center">Required qty</th>
                            <th width="15%" class="text-center">Issued qty</th>
                            <th width="15%" class="text-center" style="padding-right: 40px;">Pending qty</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="product in products" ng-show="products.length" product-directive>
                            <td>@{{ product.product.name }}</td>
                            <td width="15%" class="text-center">
                                @{{ product.available_stock }}
                                <input type="hidden" name="available_stock_in_store" value="@{{ product.available_stock }}">
                            </td>
                            <td width="15%" class="text-center">@{{ product.default_qty }}</td>
                            <td width="15%" class="td-bg-info text-center">@{{ product.available_qty }}</td>
                            <td width="15%" class="td-bg-warning text-center">@{{ product.required_qty }}</td>
                            <td width="15%" class="td-bg-success text-center">
                                <p ng-show="!edit">
                                    @{{ product.issued_qty ? product.issued_qty : 0 }}
                                </p>
                                <input type="text"
                                       class="form-control text-center @{{ hasError('data', product.id) ? 'error' : '' }}"
                                       ng-show="edit" name="issues"
                                       ng-model="issued[product.id]" style="font-size: 12px;"
                                       ng-change="updatePendingQty(product)">
                                <p class="text-danger">@{{ getErrorMsg('data', product.id) }}</p>
                            </td>
                            <td width="15%" class="text-center">@{{ product.pending_qty ? product.pending_qty : 0 }}
                            </td>
                        </tr>
                        <tr ng-repeat="product in products" ng-show="!products.length">
                            <td colspan="5">
                                There are no product to display...
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12" ng-show="edit">
                    <hr>
                    <button type="button"
                            class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar"
                            data-ng-click="saveDailyStockIssuedQty($event)">
                        <i class="fa fa-check"></i>
                        Submit
                    </button>
                    <button type="button" class="btn btn-inverse waves-effect waves-light"
                            data-ng-click="closeSideBar($event)">
                        <i class="fa fa-remove"></i> Cancel
                    </button>
                </div>
            </div>
            {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
</div>
@section('script')
    @parent
    @include('_inc.daily-stock._inc.script')
@endsection