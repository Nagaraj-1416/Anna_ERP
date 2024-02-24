<div class="card">
    <div class="card-body">
        <h3><b>PRODUCTS</b> <span class="pull-right">Total Products: {{ count($products) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Available Qty</th>
                    </tr>
                </thead>
                <tbody>
                @if($products)
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('setting.product.show', [$product]) }}">
                                    {{ $product->code }}
                                </a>
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->type }}</td>
                            <td>{{ $product->stock->available_stock or '0' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">No Products Found...</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>