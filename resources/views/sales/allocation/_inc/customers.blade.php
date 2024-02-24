<div class="card">
    <div class="card-body">
        <h3><b>CUSTOMERS</b> <span class="pull-right">Total Customers: {{ count($customers) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Mobile</th>
                </tr>
                </thead>
                <tbody>
                @if($customers)
                    @foreach($customers as $customer)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('sales.customer.show', [$customer->customer_id]) }}">
                                    {{ $customer->customer->code }}
                                </a>
                            </td>
                            <td>{{ $customer->customer->full_name }}</td>
                            <td>{{ $customer->customer->phone }}</td>
                            <td>{{ $customer->customer->mobile }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">No Customers Found...</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

