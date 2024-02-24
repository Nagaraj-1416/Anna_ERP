<div class="card">
    <div class="card-body" style="background-color: #EFEFEF;">
        <h4 class="box-title"><b>Products</b></h4>
        <h6 style="padding-top: 2px; padding-bottom: 15px;">
            <span>Pick products if you want to mark <code>RETURN, SHORTAGE, DAMAGE & RESTORE</code> Qty</span>
            <br>
            <small class="error">@{{ hasError('products', 'id', true) }}</small>
        </h6>
        <div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 8px;">
                        <input type="checkbox" id="product_select_all"
                               name="product_select_all"
                               class="chk-col-cyan "
                               ng-click="handleProductCheckAll($event)" {{ old('product_select_all') ? 'checked' : '' }}>
                        <label for="product_select_all"></label>
                    </th>
                    <th>PRODUCT NAME</th>
                    <th style="width: 10%;" class="text-info">TOTAL QTY</th>
                    <th style="width: 10%;" class="text-warning">SOLD QTY</th>
                    <th style="width: 10%;" class="text-warning">REPLACED QTY</th>
                    <th style="width: 10%;" class="text-warning">EXCESS QTY</th>
                    <th style="width: 10%;" class="text-green">AVAILABLE QTY</th>
                    <th style="width: 10%;" class="text-purple">RETURNED QTY</th>
                    <th style="width: 10%;" class="text-danger">SHORTAGE QTY</th>
                    <th style="width: 10%;" class="text-danger">DAMAGED QTY</th>
                    <th style="width: 10%;">RESTORE QTY</th>
                </tr>
                </thead>
                <tbody>
                @if($products->count())
                    @foreach($products as $product)
                        <tr>
                            <td style="width: 8px;">
                                <div class="demo-checkbox">
                                    <input type="checkbox" id="{{ 'md_checkbox_29_' . $product->id }}"
                                       name="products[id][{{ $product->id }}]"
                                       class="chk-col-cyan product-check"
                                       {{ getOldValueForHandover(old(), 'products', 'id' ,$product->id) ? 'checked' : ''}}
                                       {{ $product->returned_qty || $product->shortage_qty || $product->damaged_qty ? 'checked disabled' : '' }}
                                    >
                                    <label for="{{ 'md_checkbox_29_' . $product->id }}"></label>
                                </div>
                            </td>
                            <td>{{ $product->product->name ?? 'None' }}</td>
                            <td style="width: 10%;">{{ ($product->quantity + $product->cf_qty) }}</td>
                            <td style="width: 10%;">{{ $product->sold_qty ?? 0}}</td>
                            <td style="width: 10%;">{{ $product->replaced_qty ?? 0}}</td>
                            <td style="width: 10%;">{{ $product->excess_qty ?? 0}}</td>
                            <td style="width: 10%;">{{  getAvailableQty($product) }}</td>
                            <td style="width: 10%;">
                                <input class="form-control"
                                       id="returned_qty"
                                       autocomplete="off"
                                       ng-class="" type="text" name="products[returned_qty][{{ $product->id }}]"
                                       value="{{ $product->returned_qty ?? 0 }}" readonly />
                            </td>
                            <td style="width: 10%;">
                                <input class="form-control" name="products[shortage_qty][{{ $product->id }}]" type="text"
                                       id="shortage_qty"
                                       autocomplete="off"
                                       ng-class="hasErrorForProduct('products.shortage_qty.{{$product->id}}') ? 'error' : ''"
                                       value="{{ getOldValueForHandover(old(), 'products', 'shortage_qty' ,$product->id) ?? $product->shortage_qty ?? 0 }}">
                                <p class="form-control-feedback error"
                                   ng-show="hasErrorForProduct('products.shortage_qty.{{$product->id}}')">
                                    {{ $errors->first('products.shortage_qty.' . $product->id) }}
                                </p>
                            </td>
                            <td style="width: 10%;">
                                <input class="form-control" name="products[damaged_qty][{{ $product->id }}]" type="text"
                                       id="damaged_qty"
                                       autocomplete="off"
                                       ng-class=""
                                       value="{{ $product->damaged_qty ?? 0 }}" readonly >
                                <p class="form-control-feedback error"
                                   ng-show="hasErrorForProduct('products.damaged_qty.{{$product->id}}')">
                                    {{ $errors->first('products.damaged_qty.' . $product->id) }}
                                </p>
                            </td>
                            <td style="width: 10%;">
                                <div class="form-group">
                                    <input class="form-control" name="products[restore_qty][{{ $product->id }}]" type="text"
                                           id="restore_qty"
                                           autocomplete="off"
                                           ng-class="hasErrorForProduct('products.restore_qty.{{$product->id}}') ? 'error' : ''"
                                           value="{{ getOldValueForHandover(old(), 'products', 'restore_qty' ,$product->id) ?? 0 }}">
                                    <p class="form-control-feedback error"
                                       ng-show="hasErrorForProduct('products.restore_qty.{{$product->id}}')">
                                        {{ $errors->first('products.restore_qty.' . $product->id) }}
                                    </p>
                                </div>
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