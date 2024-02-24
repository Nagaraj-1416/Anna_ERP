<div class="row">
    <div class="col-md-3">
        {!! form()->bsText('invoice_date', 'Invoice date', isset($order) ? $order->order_date : null, ['placeholder' => 'pick an invoice date', 'class' => 'form-control datepicker']) !!}
    </div>
    <div class="col-md-3">
        {!! form()->bsText('due_date', 'Due date', isset($order) ? $order->order_date : null, ['placeholder' => 'pick invoice due date', 'class' => 'form-control datepicker']) !!}
    </div>
    {{--<div class="col-md-6">
        <div class="form-group required {{ $errors->has('invoice_type') ? 'has-danger' : '' }}">
            <label class="control-label">Invoice type</label>
            <div class="demo-radio-button">
                <input name="invoice_type" value="Invoice" type="radio" class="with-gap invoice-type" id="Invoice" checked>
                <label for="Invoice">Invoice</label>
                <input name="invoice_type" value="Proforma Invoice" type="radio" class="with-gap invoice-type" id="Proforma Invoice">
                <label for="Proforma Invoice">Proforma Invoice</label>
            </div>
            <p class="form-control-feedback">{{ $errors->first('invoice_type') }}</p>
        </div>
    </div>--}}
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table color-bordered-table muted-bordered-table">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th style="width: 50%;">Items & Description</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Rate</th>
                    <th class="text-right">Discount</th>
                    <th class="text-right" width="15%">Total</th>
                </tr>
                </thead>
                <tbody>
                @if(count($items))
                    @foreach($items as $itemKey => $item)
                        <tr>
                            <td class="text-center">{{ $itemKey+1 }}</td>
                            <td>
                                {{ $item->name }}<br>
                                <small>{{ $item->pivot->notes }}</small>
                            </td>
                            <td class="text-center">{{ $item->pivot->quantity }}</td>
                            <td class="text-right">{{ number_format($item->pivot->rate, 2) }}</td>
                            <td class="text-right">
                                {{ number_format($item->pivot->discount, 2) }}
                                {{ $item->pivot->discount_type == 'Percentage' ? '('.$item->pivot->discount_rate.'%)' : ''}}
                            </td>
                            <td class="text-right">{{ number_format($item->pivot->amount, 2) }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td colspan="5" class="text-right vm"><b>Invoice Amount</b></td>
                    <td>
                        <div class="form-group required {{ $errors->has('amount') ? 'has-danger' : '' }}">
                            <input type="text" name="amount" class="form-control text-right" placeholder="enter invoice amount" value="{{ old('amount', $pendingOrderAmount) }}">
                            <p class="form-control-feedback">{{ $errors->first('amount') }}</p>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row m-t-10">
    <div class="col-md-12">
        {!! form()->bsTextarea('invoice_notes', 'Notes', null, ['placeholder' => 'enter invoice related notes here...', 'rows' => '4'], false) !!}
    </div>
</div>