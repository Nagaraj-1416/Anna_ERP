<div class="p-20">
    <div class="row">
        <div class="col-lg-4">
            <h4 class="card-title">Sales</h4>
            <h6 class="card-subtitle">Get more insight into your income by analyzing your company's sales.</h6>
            <ul class="list-icons">
                <li><a href="{{ route('report.sales.summary') }}"><i class="fa fa-check text-info"></i> Sales Summary</a></li>
                <li><a href="{{ route('report.sales.by.customer') }}"><i class="fa fa-check text-info"></i> Sales by Customer</a></li>
                <li><a href="{{ route('report.sales.by.product') }}"><i class="fa fa-check text-info"></i> Sales by Product</a></li>
                <li><a href="{{ route('report.sales.by.product.category') }}"><i class="fa fa-check text-info"></i> Sales by Product Category</a></li>
                <li><a href="{{ route('report.sales.by.sales.rep') }}"><i class="fa fa-check text-info"></i> Sales by Sales Rep</a></li>
                <li><a href="{{ route('report.sales.by.sales.location') }}"><i class="fa fa-check text-info"></i> Sales by Sales Locations</a></li>
                <li><a href="{{ route('report.monthly.sales') }}"><i class="fa fa-check text-info"></i> Monthly Sales</a></li>
            </ul>
        </div>
        <div class="col-lg-4">
            <h4 class="card-title">Payments Received</h4>
            <h6 class="card-subtitle">View statistics on payments received and review.</h6>
            <ul class="list-icons">
                <li><a href="{{ route('report.payments.received') }}"><i class="fa fa-check text-info"></i> Payments Received</a></li>
                <li><a href="{{ route('report.credit.details') }}"><i class="fa fa-check text-info"></i> Credit Details</a></li>
            </ul>
        </div>
        <div class="col-lg-4">
            <h4 class="card-title">Receivables</h4>
            <h6 class="card-subtitle">View statistics on receivables and review.</h6>
            <ul class="list-icons">
                <li><a href="{{ route('report.customer.balance') }}"><i class="fa fa-check text-info"></i> Customer Balances</a></li>
                <li><a href="{{ route('report.aging.summary') }}"><i class="fa fa-check text-info"></i> Aging Summary</a></li>
                <li><a href="{{ route('report.aging.details') }}"><i class="fa fa-check text-info"></i> Aging Details</a></li>
                <li><a href="{{ route('report.sales.order.details') }}"><i class="fa fa-check text-info"></i> Sales Order Details</a></li>
                <li><a href="{{ route('report.invoice.details') }}"><i class="fa fa-check text-info"></i> Sales Invoice Details</a></li>
                <li><a href="{{ route('report.estimate.details') }}"><i class="fa fa-check text-info"></i> Sales Estimate Details</a></li>
                <li><a href="{{ route('report.inquiry.details') }}"><i class="fa fa-check text-info"></i> Sales Inquiry Details</a></li>
                {{--<li><a href="{{ route('report.sales.returns') }}"><i class="fa fa-check text-info"></i> Sales Returns</a></li>--}}
            </ul>
        </div>
    </div>
</div>