<div class="card">
    <div class="card-body">
        <div class="d-flex no-block">
            <h4 class="card-title">Today's Sales Summary</h4>
            <div class="ml-auto"></div>
        </div>
        <h6 class="card-subtitle">{{ carbon()->now()->format('F j, Y') }}</h6>
        <hr>
        <div class="ribbon-wrapper card">
            <div class="ribbon ribbon-success">Collection From Today's Orders / Invoices</div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Total Orders</th>
                        <th>Total Invoices</th>
                        <th>Total Sales</th>
                        <th>Paid by Cash</th>
                        <th>Paid by Cheque</th>
                        <th>Paid by Direct Deposit</th>
                        <th>Total Paid</th>
                        <th>Total Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="ribbon-wrapper card">
            <div class="ribbon ribbon-primary">Collection From Old Orders / Invoices</div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Total Orders</th>
                        <th>Total Invoices</th>
                        <th>Total Sales</th>
                        <th>Paid by Cash</th>
                        <th>Paid by Cheque</th>
                        <th>Paid by Direct Deposit</th>
                        <th>Total Paid</th>
                        <th>Total Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>