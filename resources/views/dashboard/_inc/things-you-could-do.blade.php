<!-- Things You Could Do -->
<div class="card border-info">
    <div class="card-body">
        <h3 class="card-title text-info">Things You Could Do</h3>
        <hr>
        <ul class="feeds">
            @if(isDirectorLevelStaff() || isAccountLevelStaff())
                <li>
                    <div class="bg-light-info">
                        <i class="ti-money"></i>
                    </div>
                    <a target="_blank" href="{{ route('report.sales.summary') }}">Sales Summary</a>
                </li>
                <li>
                    <div class="bg-light-info">
                        <i class="ti-blackboard"></i>
                    </div>
                    <a target="_blank" href="{{ route('company.stats') }}">Companies Stats</a>
                </li>
                <li>
                    <div class="bg-light-info">
                        <i class="ti-money"></i>
                    </div>
                    <a target="_blank" href="{{ route('sales.stats') }}">Sales Stats</a>
                </li>
                <li>
                    <div class="bg-light-info">
                        <i class="ti-user"></i>
                    </div>
                    <a target="_blank" href="{{ route('rep.stats') }}">Rep Stats</a>
                </li>
                <li>
                    <div class="bg-light-info">
                        <i class="ti-location-pin"></i>
                    </div>
                    <a target="_blank" href="{{ route('visit.stats') }}">Sales Visits</a>
                </li>
            @endif
            {{--@if(isDirectorLevelStaff() || isAccountLevelStaff() || isShopLevelStaff())
                <li>
                    <div class="bg-light-info">
                        <i class="ti-money"></i>
                    </div>
                    <a target="_blank" href="{{ route('cash.sales.index') }}">Cash Sales</a>
                </li>
            @endif--}}
            <li>
                <div class="bg-light-info">
                    <i class="ti-book"></i>
                </div>
                <a target="_blank" href="{{ route('finance.general.ledger.index') }}">General Ledgers</a>
            </li>
            <li>
                <div class="bg-light-info">
                    <i class="ti-book"></i>
                </div>
                <a target="_blank" href="{{ route('report.finance.customer.ledger') }}">Customer Ledgers</a>
            </li>
            <li>
                <div class="bg-light-info">
                    <i class="ti-book"></i>
                </div>
                <a target="_blank" href="{{ route('report.expense.by.rep') }}">Expenses Ledgers (Van)</a>
            </li>
            <li>
                <div class="bg-light-info">
                    <i class="ti-book"></i>
                </div>
                <a target="_blank" href="{{ route('report.expense.details') }}">Expenses Ledgers (Company)</a>
            </li>
            {{--@can('create', new \App\Customer())
                <li>
                    <div class="bg-light-info">
                        <i class="ti-user"></i>
                    </div>
                    <a target="_blank" href="{{ route('sales.customer.create') }}">New Customer</a>
                </li>
            @endcan
            @can('create', new \App\SalesOrder())
                <li>
                    <div class="bg-light-info">
                        <i class="ti-shopping-cart"></i>
                    </div>
                    <a target="_blank" href="{{ route('sales.order.create') }}">New Sales Order</a>
                </li>
            @endcan--}}
            {{--@can('create', new \App\Estimate())
                <li>
                    <div class="bg-light-info">
                        <i class="ti-receipt"></i>
                    </div>
                    <a target="_blank" href="{{ route('sales.estimate.create') }}">New Sales Estimate</a>
                </li>
            @endcan--}}
            {{--@can('create', new \App\Supplier())
                <li>
                    <div class="bg-light-info">
                        <i class="ti-user"></i>
                    </div>
                    <a target="_blank" href="{{ route('purchase.supplier.create') }}">New Supplier</a>
                </li>
            @endcan--}}
            {{--@can('index', new \App\PurchaseOrder())
                <li>
                    <div class="bg-light-info">
                        <i class="ti-shopping-cart"></i>
                    </div>
                    <a target="_blank" href="{{ route('purchase.order.create') }}">New Purchase Order</a>
                </li>
            @endcan
            <li>
                <div class="bg-light-info">
                    <i class="ti-package"></i>
                </div>
                <a target="_blank" href="{{ route('stock.create') }}">New Stock (Manual)</a>
            </li>--}}
        </ul>
    </div>
</div>