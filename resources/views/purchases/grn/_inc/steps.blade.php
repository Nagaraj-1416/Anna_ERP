<?php $request = request()?>
<div class="ui ordered steps fluid">
    <div class="{{ $request->is('purchase/order/create') ? 'active' : '' }}
        {{ isset($order) && $request->is('purchase/order/'.$order->id.'/wizard/confirm') ? 'completed' : '' }}
        {{ isset($order) && $request->is('purchase/order/'.$order->id.'/wizard/bill') ? 'completed' : '' }}
        {{ isset($order) && isset($bill) && $request->is('purchase/order/'.$order->id.'/wizard/bill/'.$bill->id.'/payment') ? 'completed' : '' }} step">
        <div class="content">
            <div class="title">Create Order</div>
            <div class="description">Enter order related details</div>
        </div>
    </div>

    <div class="{{ isset($order) && $request->is('purchase/order/'.$order->id.'/wizard/confirm') ? 'active' : '' }}
        {{ isset($order) && $request->is('purchase/order/'.$order->id.'/wizard/bill') ? 'completed' : '' }}
        {{ isset($order) && isset($bill) && $request->is('purchase/order/'.$order->id.'/wizard/bill/'.$bill->id.'/payment') ? 'completed' : '' }} step">
        <div class="content">
            <div class="title">Confirm Order</div>
            <div class="description">Check ordered details and confirm to generate bill</div>
        </div>
    </div>

    <div class="{{ isset($order) && $request->is('purchase/order/'.$order->id.'/wizard/bill') ? 'active' : '' }}
        {{ isset($order) && isset($bill) && $request->is('purchase/order/'.$order->id.'/wizard/bill/'.$bill->id.'/payment') ? 'completed' : '' }} step">
        <div class="content">
            <div class="title">Generate Bill</div>
            <div class="description">Enter bill related details</div>
        </div>
    </div>

    <div class="{{ isset($order) && $request->is('purchase/order/'.$order->id.'/wizard/payment') ? 'active' : '' }} step">
        <div class="content">
            <div class="title">Record Payments</div>
            <div class="description">Record payments for generated bill</div>
        </div>
    </div>
</div>