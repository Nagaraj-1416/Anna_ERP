<div class="row">
    <div class="col-md-3">
        {!! form()->bsText('bill_date', 'Bill date', (!old('_token') && isset($order)) ? $order->order_date : null, ['placeholder' => 'pick a bill date', 'class' => 'form-control datepicker']) !!}
    </div>
    <div class="col-md-3">
        {!! form()->bsText('due_date', 'Due date', (!old('_token') && isset($order)) ? $order->order_date : null, ['placeholder' => 'pick bill due date', 'class' => 'form-control datepicker']) !!}
    </div>
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
                    <td colspan="5" class="text-right vm"><b>Bill Amount</b></td>
                    <td>
                        <div class="form-group required {{ $errors->has('amount') ? 'has-danger' : '' }}">
                            <input type="text" name="amount" class="form-control text-right"
                                   placeholder="enter bill amount" value="{{ old('amount', $pendingAmount) }}">
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
        {!! form()->bsTextarea('bill_notes', 'Notes', null, ['placeholder' => 'enter bill related notes here...', 'rows' => '4'], false) !!}
    </div>
</div>