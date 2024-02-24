<div class="p-20">
    <div class="row">
        <div class="col-lg-4">
            <h4 class="card-title">Purchases</h4>
            <h6 class="card-subtitle">Get more insight into your purchase by analyzing your company's purchases.</h6>
            <ul class="list-icons">
                <li><a href="{{ route('report.purchase.by.supplier') }}"><i class="fa fa-check text-info"></i> Purchase by Supplier</a></li>
                <li><a href="{{ route('report.purchase.by.product') }}"><i class="fa fa-check text-info"></i> Purchase by Product</a></li>
                <li><a href="{{ route('report.purchase.by.product.category') }}"><i class="fa fa-check text-info"></i> Purchase by Product Category</a></li>
                <li><a href="{{ route('report.monthly.purchases') }}"><i class="fa fa-check text-info"></i> Monthly Purchases</a></li>
            </ul>
        </div>
        <div class="col-lg-4">
            <h4 class="card-title">Payments Made</h4>
            <h6 class="card-subtitle">View statistics on payments made and review.</h6>
            <ul class="list-icons">
                <li><a href="{{ route('report.payments.made') }}"><i class="fa fa-check text-info"></i> Payments Made</a></li>
                <li><a href="{{ route('report.purchase.credit.details') }}"><i class="fa fa-check text-info"></i> Credit Details</a></li>
            </ul>
        </div>
        <div class="col-lg-4">
            <h4 class="card-title">Payables</h4>
            <h6 class="card-subtitle">View statistics on payables and review.</h6>
            <ul class="list-icons">
                <li><a href="{{ route('report.supplier.balance') }}"><i class="fa fa-check text-info"></i> Supplier Balances</a></li>
                <li><a href="{{ route('report.purchase.aging.summary') }}"><i class="fa fa-check text-info"></i> Aging Summary</a></li>
                <li><a href="{{ route('report.purchase.aging.details') }}"><i class="fa fa-check text-info"></i> Aging Details</a></li>
                <li><a href="{{ route('report.purchase.order.details') }}"><i class="fa fa-check text-info"></i> Purchase Order Details</a></li>
                <li><a href="{{ route('report.bill.details') }}"><i class="fa fa-check text-info"></i> Bill Details</a></li>
                {{--<li><a href="{{ route('report.purchase.returns') }}"><i class="fa fa-check text-info"></i> Purchase Returns</a></li>--}}
            </ul>
        </div>
    </div>
</div>