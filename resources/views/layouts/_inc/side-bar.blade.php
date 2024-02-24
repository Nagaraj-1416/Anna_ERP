<?php $request = request() ?>
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap">CAP1</li>
                <li class="{{ $request->is('/') || $request->is('dashboard*') ? 'active' : '' }}">
                    <a class="has-arrow" href="{{ route('dashboard') }}" aria-expanded="false"><i
                                class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard </span></a>
                </li>
                @if(isDirectorLevelStaff() || isAccountLevelStaff() || can('index', new \App\PurchaseOrder()) || can('index', new \App\Bill())
                    || can('index', new \App\SupplierCredit()) || can('index', new \App\Supplier()) || can('index', new \App\Grn()))
                    <li class="{{ $request->is('purchase*') ? 'active' : '' }}">
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-shopping"></i><span
                                    class="hide-menu">Purchase</span></a>
                        <ul aria-expanded="false" class="collapse">
                            @if(isDirectorLevelStaff() || isAccountLevelStaff())
                                <li><a href="{{ route('purchase.index') }}">Summary</a></li>
                                <li>
                                    <hr class="menu-divider">
                                </li>
                            @endif
                            @can('index', new \App\PurchaseOrder())
                                <li><a href="{{ route('purchase.order.request') }}">Requests</a></li>
                            @endcan
                            @can('index', new \App\PurchaseOrder())
                                <li><a href="{{ route('purchase.order.index') }}">Orders</a></li>
                            @endcan
                                <li><a href="{{ route('purchase.grn.index') }}">GRNs</a></li>
                            @can('index', new \App\Bill())
                                <li><a href="{{ route('purchase.bill.index') }}">Bills</a></li>
                            @endcan
                            <li><a href="{{ route('purchase.return.index') }}">Returns</a></li>
                            {{--@can('index', new \App\SupplierCredit())
                                <li><a href="{{ route('purchase.credit.index') }}">Credits</a></li>
                            @endcan--}}
                            @can('index', new \App\Supplier())
                                <li>
                                    <hr class="menu-divider">
                                </li>
                                <li><a href="{{ route('purchase.supplier.index') }}">Suppliers</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif

                {{--<li class="{{ $request->is('production*') ? 'active' : '' }}">
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-factory"></i><span class="hide-menu">Production</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="#">Summary</a></li>
                        <li><hr class="menu-divider"></li>
                        <li><a href="#">Process Master</a></li>
                        <li><a href="#">Production Planning</a></li>
                        <li><a href="#">Job Cards</a></li>
                        <li><a href="#">Finished Goods</a></li>
                    </ul>
                </li>--}}

                @if(isDirectorLevelStaff() || isAccountLevelStaff() || isStoreLevelStaff())
                    <li class="nav-devider"></li>
                    <li class="nav-small-cap">CAP2</li>
                    <li class="{{ $request->is('stock*') ? 'active' : '' }}">
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-store"></i><span
                                    class="hide-menu">Stock</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{ route('stock.summary.index') }}">Summary</a></li>
                            <li>
                                <hr class="menu-divider">
                            </li>
                            <li><a href="{{ route('stock.search') }}">Stock Search</a></li>
                            <li><a href="{{ route('stock.history') }}">Stock History</a></li>
                            <li><a href="{{ route('stock.trans.index') }}">Stock Transactions</a></li>
                            <li>
                                <hr class="menu-divider">
                            </li>
                            <li><a href="{{ route('stock.index') }}">Stocks List</a></li>
                            <li>
                                <hr class="menu-divider">
                            </li>
                            <li><a href="{{ route('stock.store.index') }}">Store Stocks</a></li>
                            <li><a href="{{ route('stock.van.index') }}">Van Stocks</a></li>
                            <li><a href="{{ route('stock.shop.index') }}">Shop Stocks</a></li>
                            <li>
                                <hr class="menu-divider">
                            </li>
                            {{--<li><a href="{{ route('stock.transfer.index') }}">Stock Transfer</a></li>--}}
                            <li><a href="{{ route('stock.damaged.index') }}">Damaged Stocks</a></li>
                            <li><a href="{{ route('stock.return.index') }}">Returned Stocks</a></li>
                            <li>
                                <hr class="menu-divider">
                            </li>
                            <li><a href="{{ route('stock.review.index') }}">Review Stocks</a></li>
                            {{--<li><a href="{{ route('stock.purchased.history.index') }}">Purchased Histories</a></li>--}}
                            {{--<li><hr class="menu-divider"></li>--}}
                            {{--<li><a href="#">Goods Receipt Notes</a></li>--}}
                            {{--<li><a href="#">Goods Issue Notes</a></li>--}}
                        </ul>
                    </li>
                @endif

                @if(isDirectorLevelStaff() || isAccountLevelStaff() || can('index', new \App\DailySale()) || can('index', new \App\SalesOrder())
                    || can('index', new \App\Invoice()) || can('index', new \App\Estimate()) || can('index', new \App\SalesInquiry())
                    || can('index', new \App\CustomerCredit()) || can('index', new \App\Customer()))
                    <li class="{{ $request->is('sales*') ? 'active' : '' }}">
                        <a class="has-arrow " href="#" aria-expanded="false"><i class="mdi mdi-sale"></i><span
                                    class="hide-menu">Sales</span></a>
                        <ul aria-expanded="false" class="collapse">
                            @if(isDirectorLevelStaff() || isAccountLevelStaff())
                                <li><a href="{{ route('sales.index') }}">Summary</a></li>
                            @endif
                            @if(isDirectorLevelStaff() || isAccountLevelStaff() || isShopLevelStaff())
                                <li><a target="_blank" href="{{ route('cash.sales.index') }}">Cash Sales</a></li>
                                <li>
                                    <hr class="menu-divider">
                                </li>
                            @endif
                            @can('index', new \App\DailySale())
                                <li><a href="{{ route('sales.credit.orders') }}">Credit Orders</a></li>
                                <li><a href="{{ route('sales.allocation.index') }}">Sales Allocations</a></li>
                                <li><a href="{{ route('daily.stock.index') }}">Stock Allocations</a></li>
                            @endcan
                                <li>
                                    <hr class="menu-divider">
                                </li>
                            @can('index', new \App\SalesOrder())
                                <li><a href="{{ route('sales.order.index') }}">Orders</a></li>
                            @endcan
                            @can('index', new \App\Invoice())
                                <li><a href="{{ route('sales.invoice.index') }}">Invoices</a></li>
                                <li><a href="{{ route('sales.return.index') }}">Returns</a></li>
                                <li>
                                    <hr class="menu-divider">
                                </li>
                            @endcan
                            @can('index', new \App\Estimate())
                                <li><a href="{{ route('sales.estimate.index') }}">Estimates</a></li>
                            @endcan
                            @can('index', new \App\SalesInquiry())
                                <li><a href="{{ route('sales.inquiries.index') }}">Inquiries</a></li>
                            @endcan
                            @can('index', new \App\CustomerCredit())
                                <li><a href="{{ route('sales.credit.index') }}">Credits</a></li>
                            @endcan
                            @can('index', new \App\Customer())
                                <li>
                                    <hr class="menu-divider">
                                </li>
                                <li><a href="{{ route('sales.customer.index') }}">Customers</a></li>
                            @endcan
                            @can('index', new \App\DailySale())
                                <li>
                                    <hr class="menu-divider">
                                </li>
                                <li class="">
                                    <a class="has-arrow" href="#" aria-expanded="false">Shortages</a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li><a href="{{ route('sales.shortage.index') }}">Cash</a></li>
                                        <li><a href="{{ route('sales.stock.shortage.index') }}">Stocks</a></li>
                                    </ul>
                                </li>
                                <li class="">
                                    <a class="has-arrow" href="#" aria-expanded="false">Excesses</a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li><a href="{{ route('sales.excess.index') }}">Cash</a></li>
                                        <li><a href="{{ route('sales.stock.excess.index') }}">Stocks</a></li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                {{--@if(isDirectorLevelStaff() || isAccountLevelStaff() || can('index', new \App\Expense()) || can('index', new \App\ExpenseReport()))--}}
                    {{--<li class="{{ $request->is('expense*') ? 'active' : '' }}">--}}
                        {{--<a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-cash-multiple"></i><span--}}
                                    {{--class="hide-menu">Expense</span></a>--}}
                        {{--<ul aria-expanded="false" class="collapse">--}}
                            {{--@if(isDirectorLevelStaff() || isAccountLevelStaff())
                                <li><a href="{{ route('expense.index') }}">Summary</a></li>
                                <li>
                                    <hr class="menu-divider">
                                </li>
                            @endif--}}
                            {{--@can('index', new \App\Expense())
                                <li><a href="{{ route('expense.receipt.index') }}">Payments</a></li>
                            @endcan--}}
                            {{--@can('index', new \App\ExpenseReport())
                                <li><a href="{{ route('expense.reports.index') }}">Reports</a></li>
                            @endcan
                            @can('approval', new \App\ExpenseReport())
                                <li>
                                    <hr class="menu-divider">
                                </li>
                                <li><a href="{{ route('expense.reports.approvals.index') }}">Report Approvals</a></li>
                            @endcan--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                {{--@endif--}}

                @can('index', new \App\Expense())
                    <li class="{{ $request->is('expense*') ? 'active' : '' }}">
                        <a class="has-arrow" href="{{ route('expense.receipt.index') }}" aria-expanded="false">
                            <i class="mdi mdi-cash-multiple"></i> <span class="hide-menu">Payments </span></a>
                    </li>
                @endcan

                @can('index', new \App\Transaction())
                    <li class="{{ $request->is('transaction*') ? 'active' : '' }}">
                        <a class="has-arrow" href="{{ route('finance.trans.index') }}" aria-expanded="false">
                            <i class="mdi mdi-receipt"></i> <span class="hide-menu">Transactions </span></a>
                    </li>
                @endcan

                <li class="nav-devider"></li>
                <li class="nav-small-cap">CAP3</li>
                @if(isDirectorLevelStaff() || isAccountLevelStaff())
                    <li class="{{ $request->is('finance*') ? 'active' : '' }}">
                        <a class="has-arrow " href="#" aria-expanded="false"><i class="mdi mdi-library-books"></i><span
                                    class="hide-menu">Finance</span></a>
                        <ul aria-expanded="false" class="collapse">
                            {{--<li><a href="{{ route('finance.index') }}">Summary</a></li>
                            <li><hr class="menu-divider"></li>--}}
                            <li class="">
                                <a class="has-arrow" href="#" aria-expanded="false">Day Books</a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ route('finance.day.book.company.index') }}">Company</a></li>
                                    <li><a href="{{ route('finance.day.book.rep.index') }}">Rep</a></li>
                                </ul>
                            </li>
                            {{--<li class="">
                                <a class="has-arrow" href="#" aria-expanded="false">Cash Books</a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ route('finance.cash.book.rep.index') }}">Rep</a></li>
                                </ul>
                            </li>--}}
                            <li><a href="{{ route('finance.commission.index', carbon()->year) }}">Sales Commission</a></li>
                            <li>
                                <hr class="menu-divider">
                            </li>
                            <li><a href="{{ route('finance.banking.index') }}">Banking</a></li>
                            <li><a href="{{ route('finance.return.cheques.index', carbon()->year) }}">Returned Cheques</a></li>
                            <li>
                                <hr class="menu-divider">
                            </li>
                            <li><a href="{{ route('finance.transfer.index') }}">Transfers</a></li>
                            {{--<li>
                                <hr class="menu-divider">
                            </li>
                            <li><a href="{{ route('finance.trans.index') }}">Transactions</a></li>--}}
                            <li>
                                <hr class="menu-divider">
                            </li>
                            {{--<li><a href="{{ route('finance.account.balance.index') }}">Account Balances</a></li>--}}
                            <li><a href="{{ route('finance.trial.balance.index') }}">Trial Balance</a></li>
                            <li><a href="{{ route('finance.general.ledger.index') }}">General Ledger</a></li>
                            <li>
                                <hr class="menu-divider">
                            </li>
                            {{--<li><a href="#">Cash Flow</a></li>--}}
                            <li><a href="#">Profit and Loss</a></li>
                            <li><a href="#">Balance Sheet</a></li>
                            <li>
                                <hr class="menu-divider">
                            </li>
                            <li><a href="{{ route('finance.account.index') }}">Chart of Accounts</a></li>
                        </ul>
                    </li>
                @endif
                {{--<li class="{{ $request->is('talent*') ? 'active' : '' }}">
                    <a class="has-arrow " href="#" aria-expanded="false"><i class="mdi mdi-account-star"></i><span class="hide-menu">Talent</span></a>
                </li>--}}
                @if(isDirectorLevelStaff() || isAccountLevelStaff())
                    <li class="{{ $request->is('report*') ? 'active' : '' }}">
                        <a class="has-arrow " href="#" aria-expanded="false">
                            <i class="mdi mdi-chart-areaspline"></i><span class="hide-menu">Reports</span>
                        </a>
                        <ul aria-expanded="false" class="collapse" style="height: 198px;">
                            <li><a href="{{ route('report.index') }}" aria-expanded="false">View All</a></li>
                            <li>
                                <hr class="menu-divider">
                            </li>
                            <li class="">
                                <a class="has-arrow" href="#" aria-expanded="false">Sales</a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ route('report.allocation.details') }}">Sales Allocation Summary</a></li>
                                    <li><a href="{{ route('report.sales.summary') }}">Sales Summary</a></li>
                                    <li><a href="{{ route('report.sales.by.customer') }}">Sales by Customer</a></li>
                                    <li><a href="{{ route('report.sales.by.product') }}">Sales by Product</a></li>
                                    <li><a href="{{ route('report.sales.by.product.category') }}">Sales by Product
                                            Category</a></li>
                                    <li><a href="{{ route('report.sales.by.sales.rep') }}">Sales by Sales Rep</a></li>
                                    <li><a href="{{ route('report.sales.by.route') }}">Sales by Route</a></li>
                                    <li><a href="{{ route('report.sales.by.sales.location') }}">Sales by Sales
                                            Locations</a></li>
                                    <li><a href="{{ route('report.monthly.sales') }}">Monthly Sales</a></li>
                                    <li>
                                        <hr class="menu-divider">
                                    </li>
                                    <li><a href="{{ route('report.payments.received') }}">Payments Received</a></li>
                                    <li><a href="{{ route('report.credit.details') }}">Credit Details</a></li>
                                    <li>
                                        <hr class="menu-divider">
                                    </li>
                                    <li><a href="{{ route('report.customer.balance') }}">Customer Balances</a></li>
                                    <li><a href="{{ route('report.aging.summary') }}">Aging Summary</a></li>
                                    <li><a href="{{ route('report.aging.details') }}">Aging Details</a></li>
                                    <li><a href="{{ route('report.sales.order.details') }}">Sales Order Details</a></li>
                                    <li><a href="{{ route('report.invoice.details') }}">Sales Invoice Details</a></li>
                                    <li><a href="{{ route('report.estimate.details') }}">Sales Estimate Details</a></li>
                                    <li><a href="{{ route('report.inquiry.details') }}">Sales Inquiry Details</a></li>
                                </ul>
                            </li>
                            <li class="">
                                <a class="has-arrow" href="#" aria-expanded="false">Credits</a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ route('report.credits.by.customer') }}">Credits by Customer</a></li>
                                    <li><a href="{{ route('report.credits.by.rep') }}">Credits by Rep</a></li>
                                    <li><a href="{{ route('report.credits.by.route') }}">Credits by Route</a></li>
                                </ul>
                            </li>
                            <li class="">
                                <a class="has-arrow" href="#" aria-expanded="false">Damages</a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ route('report.damage.by.product') }}">Damages by Product</a></li>
                                    <li><a href="{{ route('report.damage.by.route') }}">Damages by Route</a></li>
                                    <li><a href="{{ route('report.damage.by.rep') }}">Damages by Rep</a></li>
                                    {{--<li><a href="{{ route('report.damage.by.customer') }}">Damages by Customer</a></li>--}}
                                </ul>
                            </li>
                            {{--<li class="">
                                <a class="has-arrow" href="#" aria-expanded="false">Purchase</a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ route('report.purchase.by.supplier') }}">Purchase by Supplier</a>
                                    </li>
                                    <li><a href="{{ route('report.purchase.by.product') }}">Purchase by Product</a></li>
                                    <li><a href="{{ route('report.purchase.by.product.category') }}">Purchase by Product
                                            Category</a></li>
                                    <li><a href="{{ route('report.monthly.purchases') }}">Monthly Purchases</a></li>
                                    <li>
                                        <hr class="menu-divider">
                                    </li>
                                    <li><a href="{{ route('report.payments.made') }}">Payments Made</a></li>
                                    <li><a href="{{ route('report.purchase.credit.details') }}">Credit Details</a></li>
                                    <li>
                                        <hr class="menu-divider">
                                    </li>
                                    <li><a href="{{ route('report.supplier.balance') }}">Supplier Balances</a></li>
                                    <li><a href="{{ route('report.purchase.aging.summary') }}">Aging Summary</a></li>
                                    <li><a href="{{ route('report.purchase.aging.details') }}">Aging Details</a></li>
                                    <li><a href="{{ route('report.purchase.order.details') }}">Purchase Order
                                            Details</a></li>
                                    <li><a href="{{ route('report.bill.details') }}">Bill Details</a></li>
                                </ul>
                            </li>--}}
                            <li class="">
                                <a class="has-arrow" href="#" aria-expanded="false">Van Expenses</a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ route('report.expense.details') }}">By Company</a></li>
                                    <li><a href="{{ route('report.expense.by.rep') }}">By Rep</a></li>
                                </ul>
                            </li>
                            <li class="">
                                <a class="has-arrow" href="#" aria-expanded="false">Shop Expenses</a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="#">By Shop</a></li>
                                </ul>
                            </li>
                            <li class="">
                                <a class="has-arrow" href="#" aria-expanded="false">Office Expenses</a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ route('report.expense.office.by.company') }}">By Company</a></li>
                                </ul>
                            </li>
                            {{--<li class="">
                                <a class="has-arrow" href="#" aria-expanded="false">Finance</a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ route('report.finance.customer.ledger') }}">Customer Ledger</a></li>
                                </ul>
                            </li>--}}
                        </ul>
                    </li>
                @endif
                @if(isDirectorLevelStaff() || isAccountLevelStaff()
                 || can('index', new \App\Company()) || can('index', new \App\Department()) || can('index', new \App\ProductionUnit())
                 || can('index', new \App\Store()) || can('index', new \App\SalesLocation()) || can('index', new \App\BusinessType())
                 || can('index', new \App\Product()) || can('index', new \App\PriceBook()) || can('index', new \App\Vehicle())
                 || can('index', new \App\Route()) || can('index', new \App\Rep()) || can('index', new \App\Staff())
                 || can('index', new \App\Role()) || can('index', new \App\User()) || can('index', new \App\MileageRate())
                 )
                    <li class="{{ $request->is('setting*') ? 'active' : '' }}">
                        <a class="has-arrow" href="{{ route('setting.index') }}" aria-expanded="false"><i
                                    class="mdi mdi-settings"></i><span class="hide-menu">Settings</span></a>
                        <ul aria-expanded="false" class="collapse">
                            @can('index', new \App\Company())
                                <li><a href="{{ route('setting.company.index') }}">Companies</a></li>
                            @endcan
                            @can('index', new \App\Department())
                                <li><a href="{{ route('setting.department.index') }}">Departments</a></li>
                            @endcan
                            @can('index', new \App\ProductionUnit())
                                <li><a href="{{ route('setting.production.unit.index') }}">Production Units</a></li>
                            @endcan
                            @can('index', new \App\Store())
                                <li><a href="{{ route('setting.store.index') }}">Stores</a></li>
                            @endcan
                            <li>
                                <hr class="menu-divider">
                            </li>
                            @can('index', new \App\SalesLocation())
                                <li><a href="{{ route('setting.sales.location.index') }}">Sales Locations</a></li>
                            @endcan
                            @can('index', new \App\Product())
                                <li><a href="{{ route('setting.product.index') }}">Products</a></li>
                            @endcan
                            <li><a href="{{ route('setting.price.book.index') }}">Price Books</a></li>
                            <li>
                                <hr>
                            </li>
                            @can('index', new \App\Vehicle())
                                <li><a href="{{ route('setting.vehicle.index')}}">Vehicles</a></li>
                            @endcan
                            @can('index', new \App\Route())
                                <li><a href="{{ route('setting.route.index') }}">Routes</a></li>
                            @endcan
                            @can('index', new \App\Rep())
                                <li><a href="{{ route('setting.rep.index') }}">Sales Reps</a></li>
                            @endcan
                            <li>
                                <hr class="menu-divider">
                            </li>
                            @can('index', new \App\Staff())
                                <li><a href="{{ route('setting.staff.index') }}">Staff</a></li>
                            @endcan
                            @can('index', new \App\Role())
                                <li><a href="{{ route('setting.role.index') }}">Roles & Permissions</a></li>
                            @endcan
                            <li><a href="{{ route('setting.work.hour.index') }}">Work Hours</a></li>
                            <li><a href="{{ route('setting.audit.log.index') }}">Audit Logs</a></li>
                            <li>
                                <hr class="menu-divider">
                            </li>
                            <li><a href="{{ route('setting.mileage.rate.index') }}">Mileage Rate</a></li>
                            <li><a href="{{ route('setting.account.group.index') }}">Account Groups</a></li>
                            <li><hr class="menu-divider"></li>
                            <li><a href="{{ route('api.clients.index') }}">API Clients</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
