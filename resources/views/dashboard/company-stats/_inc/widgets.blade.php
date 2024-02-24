{{--<div class="row">--}}
{{--<div class="col-md-6">--}}
{{--<!-- cash flow -->--}}
{{--<div class="card">--}}
{{--<div class="card-body">--}}
{{--<h4 class="card-title"><b>Income and Expenses</b></h4>--}}
{{--<hr>--}}

{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="col-md-6">--}}
{{--<!-- cash flow -->--}}
{{--<div class="card">--}}
{{--<div class="card-body">--}}
{{--<h4 class="card-title"><b>AP and AR Balances</b></h4>--}}
{{--<hr>--}}

{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}

<div class="row">
    <div class="col-md-6">
        <div class="cus-create-preloader loading" ng-show="salesByShopLoading">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                        stroke-miterlimit="10"/>
            </svg>
        </div>
        <!-- cash flow -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><b>Sales by Shops</b></h4>
                <hr>
                <div class="orders-list slim-scroll">
                    <div class="table-responsive">
                        <table class="table color-table muted-table">
                            <thead>
                            <tr>
                                <th>SHOP NAME</th>
                                <th class="text-right">SO AMOUNT</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="shop in salesByShop.data"
                                ng-show="salesByShop.data.length">
                                <td width="50%">
                                    <a target="_blank"
                                       href="{{ url('/') }}/setting/sales-location/@{{ shop.id }}">
                                        @{{ shop.name }}
                                    </a>
                                </td>
                                <td class="text-right ">@{{ getOrderTotal(shop) | number:2 }}
                                </td>
                            </tr>
                            <tr ng-show="salesByShop.data.length">
                                <td class="text-right td-bg-info"><b>TOTAL</b></td>
                                <td width="10%" class="text-right td-bg-success"><b>@{{ salesByShop.order_total
                                        |number:2 }}</b>
                                </td>
                            </tr>
                            <tr ng-show="!salesByShop.data.length">
                                <td>No data to display...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="cus-create-preloader loading" ng-show="salesByRepLoading">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                        stroke-miterlimit="10"/>
            </svg>
        </div>
        <!-- cash flow -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><b>Sales by Reps</b></h4>
                <hr>
                <div class="orders-list slim-scroll">
                    <div class="table-responsive">
                        <table class="table color-table muted-table">
                            <thead>
                            <tr>
                                <th>REP NAME</th>
                                <th class="text-right">SO AMOUNT</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="rep in salesByRep.data"
                                ng-show="salesByRep.data.length">
                                <td width="50%">
                                    <a target="_blank"
                                       href="{{ url('/') }}/setting/sales-rep/@{{ rep.id }}">
                                        @{{ rep.name }}
                                    </a>
                                </td>
                                <td class="text-right ">@{{ getOrderTotal(rep, true) | number:2 }}
                                </td>
                            </tr>
                            <tr ng-show="salesByRep.data.length">
                                <td class="text-right td-bg-info"><b>TOTAL</b></td>
                                <td width="10%" class="text-right td-bg-success"><b>@{{ salesByRep.order_total
                                        |number:2 }}</b>
                                </td>
                            </tr>
                            <tr ng-show="!salesByRep.data.length">
                                <td>No data to display...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="cus-create-preloader loading" ng-show="salesByCustomersLoading">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                        stroke-miterlimit="10"/>
            </svg>
        </div>
        <!-- cash flow -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><b>Sales by Customers</b></h4>
                <hr>
                <div class="orders-list slim-scroll">
                    <div class="table-responsive">
                        <table class="table color-table muted-table">
                            <thead>
                            <tr>
                                <th>CUSTOMER NAME</th>
                                <th class="text-right">SO AMOUNT</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="(key, rep) in salesByCustomer.data" ng-show="getCount(salesByCustomer.data)">
                                <td colspan="8" class="no-padding-tbl-cel">
                                    <a class="m-l-10" target="_blank"
                                       href="{{ url('/') }}/setting/sales-rep/@{{  key }}"><b>@{{
                                            rep.name }}</b></a>
                                    <span class="m-r-10 pull-right"><b>@{{  rep.total | number:2 }}</b></span>
                                    <hr>
                                    <table class="table no-border m-l-5">
                                        <tbody>
                                        <tr ng-repeat="(key, customer) in rep.customers"
                                            ng-show="getCount(rep.customers)">
                                            <td class="m-l-10" width="10%">
                                                <a target="_blank"
                                                   href="{{ url('/') }}/sales/customer/@{{ key }}">
                                                    @{{ customer.name }}
                                                </a>
                                            </td>
                                            <td class="text-right m-r-10" width="15%">@{{ customer.total | number:2 }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr ng-show="getCount(salesByCustomer.data)">
                                <td class="text-right td-bg-info"><b>TOTAL</b></td>
                                <td width="15%" class="text-right td-bg-success"><b>@{{ salesByCustomer.order_total
                                        |number:2 }}</b>
                                </td>
                            </tr>
                            <tr ng-show="!getCount(salesByCustomer.data)">
                                <td>No data to display...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="cus-create-preloader loading" ng-show="purchaseBySupplierLoading">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                        stroke-miterlimit="10"/>
            </svg>
        </div>
        <!-- cash flow -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><b>Purchases by Suppliers</b></h4>
                <hr>
                <div class="orders-list slim-scroll">
                    <div class="table-responsive">
                        <table class="table color-table muted-table">
                            <thead>
                            <tr>
                                <th>SUPPLIER NAME</th>
                                <th class="text-right">PO AMOUNT</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="supplier in purchaseBySupplier.data"
                                ng-show="purchaseBySupplier.data.length">
                                <td width="50%">
                                    <a target="_blank"
                                       href="{{ url('/') }}/purchase/supplier/@{{ supplier.id }}">
                                        @{{ supplier.display_name }}
                                    </a>
                                </td>
                                <td class="text-right ">@{{ getOrderTotal(supplier) | number:2 }}
                                </td>
                            </tr>
                            <tr ng-show="purchaseBySupplier.data.length">
                                <td class="text-right td-bg-info"><b>TOTAL</b></td>
                                <td width="10%" class="text-right td-bg-success"><b>@{{ purchaseBySupplier.order_total
                                        |number:2 }}</b>
                                </td>
                            </tr>
                            <tr ng-show="!purchaseBySupplier.data.length">
                                <td>No data to display...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="cus-create-preloader loading" ng-show="salesByProductsLoading">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                        stroke-miterlimit="10"/>
            </svg>
        </div>
        <!-- cash flow -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><b>Sales by Products</b></h4>
                <hr>
                <div class="orders-list slim-scroll">
                    <div class="table-responsive">
                        <table class="table color-table muted-table">
                            <thead>
                            <tr>
                                <th>PRODUCT NAME</th>
                                <th class="text-center">PRODUCT QTY</th>
                                <th class="text-right">SO AMOUNT</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="product in salesByProducts.data"
                                ng-show="salesByProducts.data.length">
                                <td width="50%">
                                    <a target="_blank"
                                       href="{{ url('/') }}/setting/product/@{{ product.id }}">
                                        @{{ product.name }}
                                    </a>
                                </td>
                                <td class="text-center">@{{ getProductQty(product, 'sales_orders') }}
                                </td>
                                <td class="text-right ">@{{ getSalesProductTotal(product) | number:2 }}
                                </td>
                            </tr>
                            <tr ng-show="salesByProducts.data.length">
                                <td colspan="2" class="text-right td-bg-info"><b>TOTAL</b></td>
                                <td width="10%" class="text-right td-bg-success"><b>@{{ salesByProducts.order_total
                                        |number:2 }}</b>
                                </td>
                            </tr>
                            <tr ng-show="!salesByProducts.data.length">
                                <td>No data to display...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="cus-create-preloader loading" ng-show="purchaseByProductsLoading">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                        stroke-miterlimit="10"/>
            </svg>
        </div>
        <!-- cash flow -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><b>Purchases by Products</b></h4>
                <hr>
                <div class="orders-list slim-scroll">
                    <div class="table-responsive">
                        <table class="table color-table muted-table">
                            <thead>
                            <tr>
                                <th>PRODUCT NAME</th>
                                <th class="text-center">PRODUCT QTY</th>
                                <th class="text-right">PO AMOUNT</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="product in purchaseByProducts.data"
                                ng-show="purchaseByProducts.data.length">
                                <td width="50%">
                                    <a target="_blank"
                                       href="{{ url('/') }}/setting/product/@{{ product.id }}">
                                        @{{ product.name }}
                                    </a>
                                </td>
                                <td class="text-center">@{{ getProductQty(product, 'purchase_orders') }}
                                </td>
                                <td class="text-right ">@{{ getPurchaseProductTotal(product) | number:2 }}
                                </td>
                            </tr>
                            <tr ng-show="purchaseByProducts.data.length">
                                <td colspan="2" class="text-right td-bg-info"><b>TOTAL</b></td>
                                <td width="10%" class="text-right td-bg-success"><b>@{{ purchaseByProducts.order_total
                                        |number:2 }}</b>
                                </td>
                            </tr>
                            <tr ng-show="!purchaseByProducts.data.length">
                                <td>No data to display...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="cus-create-preloader loading" ng-show="customerBalanceLoading">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                        stroke-miterlimit="10"/>
            </svg>
        </div>
        <!-- cash flow -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><b>Customers Balance Summary</b></h4>
                <hr>
                <div class="orders-list slim-scroll">
                    <div class="table-responsive">
                        <table class="table color-table muted-table">
                            <thead>
                            <tr>
                                <th>CUSTOMER NAME</th>
                                <th class="text-right">BALANCE</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="customer in customerBalance.data"
                                ng-show="customerBalance.data.length">
                                <td width="50%">
                                    <a target="_blank"
                                       href="{{ url('/') }}/sales/customer/@{{ customer.id }}">
                                        @{{ customer.display_name }}
                                    </a>
                                </td>
                                <td class="text-right ">@{{ getBalance(customer) | number:2 }}
                                </td>
                            </tr>
                            <tr ng-show="customerBalance.data.length">
                                <td class="text-right td-bg-info"><b>TOTAL</b></td>
                                <td width="10%" class="text-right td-bg-success"><b>@{{ customerBalance.balance
                                        |number:2 }}</b>
                                </td>
                            </tr>
                            <tr ng-show="!customerBalance.data.length">
                                <td>No data to display...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="cus-create-preloader loading" ng-show="supplierBalanceLoading">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                        stroke-miterlimit="10"/>
            </svg>
        </div>
        <!-- cash flow -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><b>Suppliers Balance Summary</b></h4>
                <hr>
                <div class="orders-list slim-scroll">
                    <div class="table-responsive">
                        <table class="table color-table muted-table">
                            <thead>
                            <tr>
                                <th>SUPPLIER NAME</th>
                                <th class="text-right">BALANCE</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="supplier in supplierBalance.data"
                                ng-show="supplierBalance.data.length">
                                <td width="50%">
                                    <a target="_blank"
                                       href="{{ url('/') }}/purchase/supplier/@{{ supplier.id }}">
                                        @{{ supplier.display_name }}
                                    </a>
                                </td>
                                <td class="text-right ">@{{ getBalance(supplier) | number:2 }}
                                </td>
                            </tr>
                            <tr ng-show="supplierBalance.data.length">
                                <td class="text-right td-bg-info"><b>TOTAL</b></td>
                                <td width="10%" class="text-right td-bg-success"><b>@{{ supplierBalance.balance
                                        |number:2 }}</b>
                                </td>
                            </tr>
                            <tr ng-show="!supplierBalance.data.length">
                                <td>No data to display...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="cus-create-preloader loading" ng-show="expenseLoading">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                        stroke-miterlimit="10"/>
            </svg>
        </div>
        <!-- cash flow -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><b>Customers Expenses</b></h4>
                <hr>
                <div class="orders-list slim-scroll">
                    <div class="table-responsive">
                        <table class="table color-table muted-table">
                            <thead>
                            <tr>
                                <th>CUSTOMER NAME</th>
                                <th class="text-right">EXPENSE</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="customer in expense_data.customer.data"
                                ng-show="expense_data.customer.data.length">
                                <td width="50%">
                                    <a target="_blank"
                                       href="{{ url('/') }}/sales/customer/@{{ customer.id }}">
                                        @{{ customer.display_name }}
                                    </a>
                                </td>
                                <td class="text-right ">@{{ getExpenseAmount(customer) | number:2 }}
                                </td>
                            </tr>
                            <tr ng-show="expense_data.customer.data.length">
                                <td class="text-right td-bg-info"><b>TOTAL</b></td>
                                <td width="10%" class="text-right td-bg-success"><b>@{{ expense_data.customer.total |
                                        number:2 }}</b>
                                </td>
                            </tr>
                            <tr ng-show="!expense_data.customer.data.length">
                                <td>No data to display...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="cus-create-preloader loading" ng-show="expenseLoading">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                        stroke-miterlimit="10"/>
            </svg>
        </div>
        <!-- cash flow -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><b>Suppliers Expenses</b></h4>
                <hr>
                <div class="orders-list slim-scroll">
                    <div class="table-responsive">
                        <table class="table color-table muted-table">
                            <thead>
                            <tr>
                                <th>SUPPLIER NAME</th>
                                <th class="text-right">EXPENSE</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="supplier in expense_data.supplier.data"
                                ng-show="expense_data.supplier.data.length">
                                <td width="50%">
                                    <a target="_blank"
                                       href="{{ url('/') }}/purchase/supplier/@{{ supplier.id }}">
                                        @{{ supplier.display_name }}
                                    </a>
                                </td>
                                <td class="text-right ">@{{ getExpenseAmount(supplier) | number:2 }}
                                </td>
                            </tr>
                            <tr ng-show="expense_data.supplier.data.length">
                                <td class="text-right td-bg-info"><b>TOTAL</b></td>
                                <td width="10%" class="text-right td-bg-success"><b>@{{ expense_data.supplier.total |
                                        number:2 }}</b>
                                </td>
                            </tr>
                            <tr ng-show="!expense_data.supplier.data.length">
                                <td>No data to display...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>