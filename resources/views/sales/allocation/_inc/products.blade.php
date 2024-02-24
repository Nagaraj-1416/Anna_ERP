<div class="card">
    <div class="card-body">
        <h3><b>PRODUCTS</b> <span class="pull-right">Total Products: {{ count($products) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Product details</th>
                    <th>Type</th>
                    <th>Store</th>
                    <th class="text-center">Carry forward Qty</th>
                    <th class="text-center">Allocated Qty</th>
                    <th class="text-center">Sold Qty</th>
                    <th class="text-center">Returned Qty</th>
                    <th class="text-center">Shortage Qty</th>
                    <th class="text-center">Damaged Qty</th>
                    <th class="text-center">Restored Qty</th>
                    <th class="text-center">Available Qty</th>
                </tr>
                </thead>
                <tbody>
                @if($products)
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('setting.product.show', [$product->product_id]) }}">
                                    {{ $product->product->name.' ('.$product->product->code.')' }}
                                </a>
                            </td>
                            <td>{{ $product->product->type }}</td>
                            <td>{{ $product->store->name }}</td>
                            <td class="text-center">{{ $product->cf_qty ?? 0}}</td>
                            <td class="text-center">{{ $product->quantity ?? 0}}</td>
                            <td class="text-center">{{ $product->sold_qty ?? 0 }}</td>
                            <td class="text-center">{{ $product->returned_qty ?? 0 }}</td>
                            <td class="text-center">{{ $product->shortage_qty ?? 0 }}</td>
                            <td class="text-center">{{ $product->damaged_qty ?? 0 }}</td>
                            <td class="text-center">{{ $product->restored_qty ?? 0 }}</td>
                            <td class="text-center">
                                {{ ($product->quantity + $product->cf_qty) - ($product->sold_qty + $product->restored_qty) }}
                            </td>
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