<div class="card border-info">
    <div class="card-body">
        <h4 class="card-title text-info">Things You Could Do</h4>
        <hr />
        <ul class="feeds">
            <li>
                <div class="bg-light-info">
                    <i class="ti-user"></i>
                </div> <a target="_blank" href="{{ route('purchase.supplier.create') }}">New Supplier</a>
            </li>
            <li>
                <div class="bg-light-success">
                    <i class="ti-shopping-cart"></i>
                </div> <a target="_blank" href="{{ route('purchase.order.create') }}">New Purchase Order</a>
            </li>
            <li>
                <div class="bg-light-danger">
                    <i class="ti-money"></i>
                </div> <a target="_blank" href="{{ route('purchase.credit.create') }}">New Supplier Credit</a>
            </li>
        </ul>
    </div>
</div>