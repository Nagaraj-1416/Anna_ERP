<div class="card border-info">
    <div class="card-body">
        <h4 class="card-title text-info">Things You Could Do</h4>
        <hr />
        <ul class="feeds">
            <li>
                <div class="bg-light-success">
                    <i class="ti-money"></i>
                </div>
                <a target="_blank" href="{{ route('report.sales.summary') }}">Sales Summary</a>
            </li>
            <li>
                <div class="bg-light-success">
                    <i class="ti-money"></i>
                </div>
                <a target="_blank" href="{{ route('cash.sales.index') }}">Cash Sales</a>
            </li>
            <li>
                <div class="bg-light-info">
                    <i class="ti-user"></i>
                </div> <a target="_blank" href="{{ route('sales.customer.create') }}">New Customer</a>
            </li>
            <li>
                <div class="bg-light-success">
                    <i class="ti-shopping-cart"></i>
                </div> <a target="_blank" href="{{ route('sales.order.create') }}">New Sales Order</a>
            </li>
            <li>
                <div class="bg-light-primary">
                    <i class="ti-receipt"></i>
                </div> <a target="_blank" href="{{ route('sales.estimate.create') }}">New Sales Estimate</a>
            </li>
            <li>
                <div class="bg-light-warning">
                    <i class="ti-notepad"></i>
                </div> <a target="_blank" href="{{ route('sales.inquiries.create') }}">New Sales Inquiry</a>
            </li>
            <li>
                <div class="bg-light-danger">
                    <i class="ti-money"></i>
                </div> <a target="_blank" href="{{ route('sales.credit.create') }}">New Customer Credit</a>
            </li>
        </ul>
    </div>
</div>