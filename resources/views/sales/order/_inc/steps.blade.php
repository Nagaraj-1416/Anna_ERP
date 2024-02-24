<?php $request = request()?>
<div class="ui ordered steps fluid">
    <div class="{{ $request->is('sales/order/create') ? 'active' : '' }}
        {{ isset($order) && $request->is('sales/order/'.$order->id.'/wizard/confirm') ? 'completed' : '' }}
        {{ isset($order) && $request->is('sales/order/'.$order->id.'/wizard/invoice') ? 'completed' : '' }}
        {{ isset($order) && isset($invoice) && $request->is('sales/order/'.$order->id.'/wizard/invoice/'.$invoice->id.'/payment') ? 'completed' : '' }} step">
        <div class="content">
            <div class="title">Create Order</div>
            <div class="description">Enter order related details</div>
        </div>
    </div>

    <div class="{{ isset($order) && $request->is('sales/order/'.$order->id.'/wizard/confirm') ? 'active' : '' }}
        {{ isset($order) && $request->is('sales/order/'.$order->id.'/wizard/invoice') ? 'completed' : '' }}
        {{ isset($order) && isset($invoice) && $request->is('sales/order/'.$order->id.'/wizard/invoice/'.$invoice->id.'/payment') ? 'completed' : '' }} step">
        <div class="content">
            <div class="title">Confirm Order</div>
            <div class="description">Check ordered details and confirm to generate invoice</div>
        </div>
    </div>

    <div class="{{ isset($order) && $request->is('sales/order/'.$order->id.'/wizard/invoice') ? 'active' : '' }}
        {{ isset($order) && isset($invoice) && $request->is('sales/order/'.$order->id.'/wizard/invoice/'.$invoice->id.'/payment') ? 'completed' : '' }} step">
        <div class="content">
            <div class="title">Generate Invoice</div>
            <div class="description">Enter invoice related details</div>
        </div>
    </div>

    <div class="{{ isset($order) && $request->is('sales/order/'.$order->id.'/wizard/payment') ? 'active' : '' }} step">
        <div class="content">
            <div class="title">Record Payments</div>
            <div class="description">Record payments for generated invoice</div>
        </div>
    </div>
</div>