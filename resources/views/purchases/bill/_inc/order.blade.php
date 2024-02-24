<div class="card">
    <div class="card-body">
        <h4 class="card-title">Purchase Order Summary</h4>
        <hr>
        <div>
            <p>
                <b>Supplier :</b>
                <a target="_blank"
                   href="{{ route('purchase.supplier.show', [$bill->supplier]) }}">{{ $bill->supplier->display_name }}</a>
            </p>
            <p>
                <b>PO No :</b>
                <a target="_blank"
                   href="{{ route('purchase.order.show', [$bill->order]) }}">{{ $bill->order->po_no }}</a>
            </p>
            <p><b>Order Date :</b> {{ $bill->order->order_date }}</p>
            <p><b>Order Status :</b> <span
                        class="{{ statusLabelColor($bill->order->status) }}">{{ $bill->order->status }}</span>
            </p>
            <hr>
            <h3 class="card-title"><b>{{ number_format($bill->order->total, 2) }}</b></h3>
            <h6 class="card-subtitle">Purchase Order Amount</h6>
        </div>
        <div class="custom-top-margin">
            <div class="progress">
                <div class="progress-bar bg-success" role="progressbar"
                     style="width: 45%; height:10px;" aria-valuenow="25" aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
        </div>
    </div>
    <hr>
    <div class="card-body">
        <div class="row">
            <div class="col-4">
                <h4>{{ number_format(poOutstanding($bill->order)['billed'], 2) }}</h4>
                <h6 class="text-muted text-info">Total Billed</h6>
            </div>
            <div class="col-4">
                <h4>{{ number_format(poOutstanding($bill->order)['paid'], 2) }}</h4>
                <h6 class="text-muted text-success">Total Paid</h6>
            </div>
            <div class="col-4">
                <h4>{{ number_format(poOutstanding($bill->order)['balance'], 2) }}</h4>
                <h6 class="text-muted text-warning">Total Balance</h6>
            </div>
        </div>
    </div>
</div>