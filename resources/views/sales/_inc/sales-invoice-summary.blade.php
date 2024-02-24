<div class="card border-purple">
    <div class="card-body">
        <h4 class="card-title text-purple">Invoices Summary</h4>
        <h6 class="card-subtitle m-b-0">{{ carbon()->now()->format('F j, Y') }}</h6>
        <hr>
        <div class="row">
            <div class="col-12">
                <h3 class="text-warning">@{{ invSummary.totalInvoiced }}</h3>
                <h6 class="text-muted">Total Invoiced</h6>
                <hr>
                <h3 class="text-green">@{{ invSummary.totalPaid }}</h3>
                <h6 class="text-muted">Total Paid</h6>
            </div>
        </div>
    </div>
</div>