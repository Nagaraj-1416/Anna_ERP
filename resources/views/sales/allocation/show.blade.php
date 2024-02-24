@extends('layouts.master')
@section('title', 'Allocation Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row" ng-controller="AllocationController">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Allocation and Handover Details</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @can('edit', $allocation)
                                    @if(($allocation->status == 'Active' || $allocation->status == 'Draft'))
                                        @if($allocation->sales_location == 'Van')
                                        <a href="{{ route('sales.allocation.edit', [$allocation]) }}"
                                           class="btn waves-effect waves-light btn-primary btn-sm"
                                           target="_blank">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                        @endif
                                    @endif
                                @endcan

                                @if(!$allocation->salesHandover && $allocation->status != 'Canceled')
                                    @can('edit', $allocation)
                                        @if($allocation->sales_location == 'Van')
                                            <a target="_blank" href="{{ route('sales.allocation.add.customer', $allocation) }}" class="btn waves-effect waves-light btn-info btn-sm"
                                               id="">
                                                <i class="fa fa-plus"></i> Add Customers
                                            </a>
                                        @endif
                                    @endcan
                                    <a target="_blank" href="{{ route('sales.allocation.allocate.products', $allocation) }}" class="btn waves-effect waves-light btn-info btn-sm"
                                            id="">
                                        <i class="fa fa-plus"></i> Add Products
                                    </a>
                                @endif

                                @can('edit', $allocation)
                                    @if($allocation->sales_location == 'Van')
                                        @if($allocation->status == 'Active' || $allocation->status == 'Progress' && !$allocation->salesHandover)
                                            <a href="{{ route('sales.allocation.credit.order', [$allocation]) }}"
                                               class="btn waves-effect waves-light btn-warning btn-sm"
                                               target="_blank"> <i class="fa fa-plus"></i> Attach Credit Orders
                                            </a>
                                            <a href="{{ route('sales.allocation.phone.order', [$allocation]) }}"
                                               class="btn waves-effect waves-light btn-warning btn-sm"
                                               target="_blank"> <i class="fa fa-plus"></i> Attach Phone Orders
                                            </a>
                                        @endif
                                    @endif
                                    @if($allocation->sales_location == 'Van')
                                        @if($allocation->status == 'Progress' && !$allocation->salesHandover)
                                            <a href="{{ route('sales.allocation.complete', ['allocation' => $allocation]) }}" target="_blank" class="btn btn-primary btn-sm">
                                                <i class="fa fa-check"></i> Complete Allocation
                                            </a>
                                        @endif
                                        @if($allocation->status == 'Progress' && !$allocation->orders->count() && $allocation->is_logged_in == 'No')
                                            <a class="btn btn-danger btn-sm status-change" href=""
                                               data-id="{{ $allocation->id }}" data-value="Canceled">
                                                <i class="fa fa-ban"></i> Cancel Allocation
                                            </a>
                                        @endif
                                        @if($allocation->status == 'Progress' && $allocation->is_logged_in == 'Yes' && !$allocation->salesHandover)
                                            <a class="btn btn-danger btn-sm allow-mobile-login" href=""
                                               data-id="{{ $allocation->id }}">
                                                <i class="fa fa-lock"></i> Allow Mobile Login
                                            </a>
                                        @endif
                                    @endif
                                    @if(isCashierLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff())
                                        @if($allocation->sales_location == 'Van')
                                            @if($allocation->status == 'Progress' || $allocation->status == 'Completed' && $allocation->salesHandover)
                                                <a href="{{ route('sales.allocation.sheet', [$allocation]) }}"
                                                   class="btn waves-effect waves-light btn-excel btn-sm"
                                                   target="_blank"> <i class="fa fa-book"></i> View Sales Sheet
                                                </a>
                                            @endif
                                        @endif
                                    @endif
                                @endcan
                                @if($handover && $handover->status == 'Pending')
                                    <a target="_blank" href="{{ route('sales.allocation.add.expense', $allocation) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-plus"></i> Add Expense
                                    </a>
                                @endif
                                @if($handover)
                                    @if(isDirectorLevelStaff() || isStoreLevelStaff() || isAccountLevelStaff())
                                        @if(!isNextDayAllocationAvailable($allocation))
                                            <a target="_blank" href="{{ route('sales.allocation.get.sold.qty', $allocation) }}" class="btn btn-warning btn-sm">
                                                <i class="fa fa-edit"></i> Update Sold Qty
                                            </a>
                                            <a target="_blank" href="{{ route('sales.allocation.get.actual.qty', $allocation) }}" class="btn btn-danger btn-sm">
                                                <i class="fa fa-window-restore"></i> Bulk Stocks Restore
                                            </a>
                                        @endif
                                    @endif
                                @endif
                            </div>
                            <div class="pull-right">
                                {{--@can('export', $allocation)
                                    <a href="{{ route('sales.allocation.export', [$allocation]) }}"
                                       class="btn waves-effect waves-light btn-pdf btn-sm">
                                        <i class="fa fa-file-pdf-o"></i> Export to PDF
                                    </a>
                                @endcan--}}
                                {{--@can('print', $allocation)
                                    <a target="_blank" href="{{ route('sales.allocation.print', [$allocation]) }}"
                                       class="btn waves-effect waves-light btn-inverse btn-sm">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                @endcan--}}

                                @can('edit', $allocation)
                                    @if($allocation->sales_location == 'Van')
                                        @if($allocation->status == 'Progress')
                                            @if(!$allocation->salesHandover)
                                                <a href="{{ route('sales.allocation.change.actors', [$allocation]) }}"
                                                   class="btn waves-effect waves-light btn-danger btn-sm"
                                                   target="_blank">
                                                    <i class="fa fa-pencil"></i> Change Rep / Driver / Labour
                                                </a>
                                            @endif
                                        @endif
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- allocation related details -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            @if($handover)
                                @if(!count($expenses))
                                    <div class="alert alert-danger text-center">
                                        There are no expenses recorded for this allocation, please enter relevant expense details to continue with confirm handover.
                                    </div>
                                @endif
                            @endif

                            @include('sales.allocation._inc.handover')
                            @if($allocation->sales_location == 'Van')
                                @include('sales.allocation._inc.customer.index')
                            @endif
                            @include('sales.allocation._inc.product.index', $allocation)
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>ALLOCATION</b> |
                                    <small class="{{ statusLabelColor($allocation->status) }}">
                                        {{ $allocation->status }}
                                    </small>
                                    <span class="pull-right">#{{ $allocation->code }}</span></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Allocation type
                                                :</b> {{ $allocation->day_type }} {{ $allocation->day_type == 'Single' ? 'Day' : 'Days' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Allocation from :</b> {{ $allocation->from_date }}</p>
                                    </div>
                                    <div class="col-md-9">
                                        <p><b>Allocation to :</b> {{ $allocation->to_date }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Sales location type :</b> {{ $allocation->sales_location }}</p>
                                    </div>
                                    {{--<div class="col-md-9">
                                        <p><b>Sales location :</b>
                                            <a target="_blank"
                                               href="{{ route('setting.sales.location.show', [$allocation->salesLocation]) }}">
                                                {{ $allocation->salesLocation->name.' ('.$allocation->code.')' }}
                                            </a>
                                        </p>
                                    </div>--}}
                                    @if($allocation->sales_location == 'Van')
                                        <div class="col-md-9">
                                            <p><b>Route :</b>
                                                <a target="_blank"
                                                   href="{{ route('setting.route.show', [$allocation->route]) }}">
                                                    {{ $allocation->route->name.' ('.$allocation->route->code.')' }}
                                                </a>
                                            </p>
                                        </div>
                                    @else
                                        <div class="col-md-9">
                                            <p><b>Sales location :</b>
                                                <a target="_blank"
                                                   href="{{ route('setting.sales.location.show', [$allocation->sales_location_id]) }}">
                                                    {{ $allocation->salesLocation->name.' ('.$allocation->salesLocation->code.')' }}
                                                </a>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                @if($allocation->sales_location == 'Van')
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><b>Rep :</b>
                                                <a target="_blank"
                                                   href="{{ route('setting.rep.show', [$allocation->rep]) }}">
                                                    {{ $allocation->rep->name.' ('.$allocation->rep->code.')' }}
                                                </a>
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><b>Driver :</b>
                                                <a target="_blank"
                                                   href="{{ route('setting.staff.show', [$allocation->driver]) }}">
                                                    {{ $allocation->driver->short_name.' ('.$allocation->driver->code.')' }}
                                                </a>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><b>Labours :</b>
                                                @foreach(getAllocationLabours($allocation) as $labour)
                                                    {{ $labour->short_name }}@if(!$loop->last), @endif
                                                @endforeach
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><b>Vehicle :</b>
                                                <a target="_blank"
                                                   href="{{ route('setting.vehicle.show', [$allocation->vehicle]) }}">
                                                    {{ $allocation->vehicle->vehicle_no  ?? 'None'}}
                                                </a>
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><b>Odo meter start reading :</b>
                                                {{ $allocation->odoMeterReading->starts_at  ?? 'None'}}
                                            </p>
                                        </div>

                                        <div class="col-md-3">
                                            <p><b>Odo meter end reading :</b>
                                                {{ $allocation->odoMeterReading->ends_at  ?? 'None'}}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Company :</b>
                                            <a target="_blank"
                                               href="{{ route('setting.company.show', [$allocation->company]) }}">
                                                {{ $allocation->company->name }}
                                            </a>
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Prepared by :</b> {{ $allocation->preparedBy->name or 'None' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><b>Prepared at
                                                :</b> {{ date("F j, Y, g:i a", strtotime($allocation->created_at)) }}
                                        </p>
                                    </div>
                                </div>
                                @if($allocation->start_time)
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><b>Start Time :</b>
                                                {{date("F j, Y, g:i:s a", strtotime( $allocation->start_time))  }}
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><b>End Time
                                                    :</b> {{ date("F j, Y, g:i:s  a", strtotime( $allocation->end_time))  }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><b>Time Taken
                                                    :</b> {{ getDifferentTime($allocation->start_time, $allocation->end_time) }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{--<div class="card">
                                <div class="card-body">
                                    <div id="map" style="width: 100%; height: 600px;"></div>
                                </div>
                            </div>--}}

                            @if(isCashierLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff())
                                <!-- customers list -->
                                @if($allocation->sales_location == 'Van')
                                    @include('sales.allocation._inc.anquler.customer', ['customers' => $allocation->customers])
                                @endif
                            @endif

                            <!-- products list -->
                            @include('sales.allocation._inc.anquler.product', ['products' => $allocation->items])

                            <!-- products stock moving history list -->
                            @include('sales.allocation._inc.anquler.product-history', ['products' => $allocation->items])

                            @if(isCashierLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff())
                                @if($allocation->sales_location == 'Van')
                                <!-- credit orders list -->
                                    @include('sales.allocation._inc.anquler.credit-orders')
                                @endif
                            @endif

                            @if(isCashierLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff())
                            <div class="card">
                                <div class="card-body">
                                    <h3><b>ATTACHMENTS</b> <span
                                                class="pull-right">Total: {{ count($allocation->documents) }}</span>
                                    </h3>
                                    <hr>
                                    @include('_inc.document.view', ['model' => $allocation])
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- recent logs -->
                        <div class="col-md-3">
                            @if(isCashierLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff())
                                @if($allocation->status == 'Draft')
                                    <div class="card border-warning text-center send-estimate-panel">
                                        <div class="card-body">
                                            <h3 class="card-title text-danger"><i class="fa fa-clock-o"></i>
                                                Active Allocation
                                            </h3>
                                            <p class="card-subtitle">
                                                This is a <code>DRAFT</code> allocation. Click on below button to activate
                                                and send it for approval.</p>
                                            <a class="btn btn-danger status-change" data-value="Active" href=""
                                               data-id="{{ $allocation->id }}">Activate
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @if($allocation->status == 'Active')
                                    <div class="card border-warning text-center estimate-approval-panel">
                                        <div class="card-body">
                                            <h3 class="card-title text-danger"><i class="fa fa-clock-o"></i> Approval
                                                Pending</h3>


                                            <p class="card-subtitle">
                                                This allocation is waiting for approval, so that allocation can be mark as
                                                <code>Progress</code>. You can either <code>Cancel</code> this allocation.
                                            </p>
                                            <a class="btn btn-danger status-change" href=""
                                               data-id="{{ $allocation->id }}" data-value="Progress">
                                                <i class="fa fa-check"></i> Approve
                                            </a>
                                            <a class="btn btn-danger status-change" href=""
                                               data-id="{{ $allocation->id }}" data-value="Canceled">
                                                <i class="fa fa-check"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <div class="card border-default">
                                <div class="card-body">
                                    <h4 class="card-title text-megna">Allocation Summary</h4>
                                    <hr>
                                    <div>
                                        <p>
                                            <b>Products :</b> {{ $allocation->items()->count() }}
                                        </p>
                                        <p>
                                            <b>Customers :</b> {{ $allocation->customers()->count() }}
                                        </p>
                                        <p>
                                            <b>Visited Customers :</b> <span
                                                    class="text-green">{{ $allocation->visited_customers }}</span>
                                        </p>
                                        <p>
                                            <b>Not Visited Customers :</b> <span
                                                    class="text-danger">{{ $allocation->not_visited_customers }}</span>
                                        </p>
                                    </div>

                                    @if(isset($handover) && $handover->status == 'Confirmed')
                                        <hr>
                                        <h6 class="card-title text-megna">Next Day Allocation Summary</h6>
                                        <hr>
                                        <div>
                                            <p>
                                                <b>Route
                                                    :</b> {{ $allocation->dailyStock ? $allocation->dailyStock->route->name : 'None' }}
                                            </p>
                                            <p>
                                                <b>Rep
                                                    :</b> {{ $allocation->dailyStock ? $allocation->dailyStock->rep->name : 'None' }}
                                            </p>
                                            <p>
                                                <b>Store
                                                    :</b> {{ $allocation->dailyStock ? $allocation->dailyStock->store->name : 'None' }}
                                            </p>
                                            <p><a href="" id="daily-stock-add-btn"
                                                  data-id="{{ $allocation->dailyStock->id ?? null }}">View More
                                                    Details</a></p>
                                        </div>
                                    @endif
                                </div>

                            </div>

                            @if(isDirectorLevelStaff() || isAccountLevelStaff())
                                {{--<div class="card border-purple">
                                    <div class="card-body">
                                        <h4 class="card-title text-purple">
                                            Orders Summary
                                            <span class="pull-right">Total: {{ count(allocationOrders($allocation)) }}</span>
                                        </h4>
                                        <div class="scrollable-widget">
                                            <table class="table stylish-table m-t-10">
                                                <tbody>
                                                @if(count(allocationOrders($allocation)))
                                                    @foreach(allocationOrders($allocation) as $order)
                                                        <tr>
                                                            @if($order->customer)
                                                                <td class="text-center">
                                                                <span>
                                                                    <img src="{{route('sales.customer.logo', [$order->customer_id])}}"
                                                                         alt="user" width="60" class="img-circle"><br/>
                                                                    <small class="{{ statusLabelColor($order->status) }}">
                                                                        <b>{{ $order->status }}</b>
                                                                    </small>
                                                                </span>
                                                                </td>
                                                            @endif
                                                            <td>
                                                                <h6><a target="_blank"
                                                                       href="{{ route('sales.order.show', [$order]) }}">
                                                                        {{ $order->ref }}
                                                                        ({{ number_format($order->total) }})
                                                                    </a></h6>
                                                                <small class="text-muted">
                                                                    {{ $order->customer->display_name ?? 'None' }}<br/>
                                                                    <b>Order
                                                                        at:</b> {{ date("F j, Y, g:i a", strtotime($order->created_at)) }}
                                                                    <br/>
                                                                    <b>Distance:</b>
                                                                    @if($order->customer && $order->customer->gps_lat && $order->customer->gps_long && $order->gps_lat && $order->gps_long)
                                                                        <a target="_blank" href="{{ route('map.index', [
                                                                    'startLat' => $order->customer->gps_lat,
                                                                    'startLng' => $order->customer->gps_long,
                                                                    'startInfo' => json_encode(['heading' => $order->customer->display_name, 'code' => $order->customer->tamil_name]),
                                                                    'endLat' => $order->gps_lat,
                                                                    'endLng' => $order->gps_long,
                                                                    'endInfo' => json_encode(['heading' => $order->ref, 'date' => date("F j, Y, g:i a", strtotime($order->created_at)), 'rep' => $order->salesRep->name ?? ''])
                                                                    ]) }}">
                                                                         <span id="distance-{{ $order->id }}"> {{ round($order->distance, 2) }}
                                                                             KM</span>
                                                                        </a>
                                                                    @else
                                                                        <span id="distance-{{ $order->id }}"> {{ round($order->distance, 2) }}
                                                                            KM</span>
                                                                    @endif
                                                                    --}}{{--<span class="{{ statusLabelColor($order->status) }}">
                                                                        <b>{{ $order->status }}</b>
                                                                    </span>--}}{{--
                                                                    @if($order->is_credit_sales == 'Yes')
                                                                        <br/>
                                                                        <span><b class="text-danger">CREDIT SALE</b></span>
                                                                    @endif
                                                                    @if($order->is_order_printed == 'Yes')
                                                                        <br/>
                                                                        <span class="text-green">Order is printed!</span>
                                                                    @else
                                                                        <br/>
                                                                        <span class="text-danger">Order is not printed yet!</span>
                                                                    @endif
                                                                </small>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="2" class="text-muted">
                                                            <small>No orders found...</small>
                                                        </td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>--}}

                                @if($allocation->sales_location == 'Van')
                                    {{--<div class="card border-info">
                                        <div class="card-body">
                                            <h4 class="card-title text-info">
                                                Non-Route Customers & Orders
                                                <span class="pull-right">Total: {{ count($nonRoutedCustomers) }}</span>
                                            </h4>
                                            <hr>
                                            <div class="scrollable-widget">
                                                @if(count($nonRoutedCustomers))
                                                    @foreach($nonRoutedCustomers as $nonRoutedCustomer)
                                                        <a href="{{ route('sales.customer.show', [$nonRoutedCustomer->customer]) }}">
                                                            {{ $nonRoutedCustomer->customer->display_name }}
                                                        </a>
                                                        <table class="table stylish-table m-t-10">
                                                            <tbody>
                                                            @if(count(getNonRoutedCusOrders($nonRoutedCustomer->customer, $allocation)))
                                                                @foreach(getNonRoutedCusOrders($nonRoutedCustomer->customer, $allocation) as $nonRoutedOrder)
                                                                    <tr>
                                                                        <td>
                                                                            <h6>
                                                                                <a target="_blank"
                                                                                   href="{{ route('sales.order.show', [$nonRoutedOrder]) }}">
                                                                                    {{ $nonRoutedOrder->ref }}
                                                                                    ({{ number_format($nonRoutedOrder->total) }}
                                                                                    )
                                                                                </a>
                                                                            </h6>
                                                                            <small class="text-muted">
                                                                                <b>Order
                                                                                    at:</b> {{ date("F j, Y, g:i a", strtotime($nonRoutedOrder->created_at)) }}
                                                                                <br/>
                                                                                <b>Distance:</b>
                                                                                @if($nonRoutedOrder->is_credit_sales == 'Yes')
                                                                                    <br/>
                                                                                    <span><b class="text-danger">CREDIT SALE</b></span>
                                                                                @endif
                                                                                @if($nonRoutedOrder->is_order_printed == 'Yes')
                                                                                    <br/>
                                                                                    <span class="text-green">Order is printed!</span>
                                                                                @else
                                                                                    <br/>
                                                                                    <span class="text-danger">Order is not printed yet!</span>
                                                                                @endif
                                                                            </small>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td colspan="2" class="text-muted">
                                                                        <small>No orders created...</small>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            </tbody>
                                                        </table>
                                                    @endforeach
                                                @else
                                                    <small class="text-muted m-l-10">No details found...</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>--}}
                                @endif

                                @if($allocation->sales_location == 'Van')
                                    {{--<div class="card border-danger">
                                        <div class="card-body">
                                            <h4 class="card-title text-danger">
                                                Returns Summary
                                                <span class="pull-right">Total: {{ count(allocationReturns($allocation)) }}</span>
                                            </h4>
                                            <div class="scrollable-widget">
                                                <table class="table stylish-table m-t-10">
                                                    <tbody>
                                                    @if(count(allocationReturns($allocation)))
                                                        @foreach(allocationReturns($allocation) as $return)
                                                            <tr>
                                                                <td class="text-center" style="width: 25%;">
                                                                    <span>
                                                                        <img src="{{route('sales.customer.logo', [$return->customer_id])}}"
                                                                             alt="user" width="60" class="img-circle">
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <h6><a target="_blank"
                                                                           href="{{ route('sales.return.show', [$return]) }}">
                                                                            {{ $return->code }}
                                                                        </a>
                                                                    </h6>
                                                                    <small class="text-muted">
                                                                        {{ $return->customer->display_name }}<br/>
                                                                        <b>Returned amount:</b> {{ $return->return_amount }}
                                                                        <br/>
                                                                        <b>No of items:</b> {{ $return->no_of_items }}
                                                                    </small>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="2" class="text-muted">
                                                                <small>No returns found...</small>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>--}}
                                @endif
                            @endif

                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $allocation])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $allocation, 'modelName' => 'Allocation'])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('_inc.daily-stock.add')
@endsection

@section('script')
    @include('general.comment.script', ['modelId' => $allocation->id])
    @include('_inc.document.script', ['model' => $allocation])
    @include('sales.allocation._inc.customer.script', ['model' => $allocation])
    @include('sales.allocation._inc.product.script', ['model' => $allocation])
    @include('general.distance-calculator.index')
    @include('_inc.daily-stock._inc.script')
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    @include('general.helpers')
    <script>
        $('.status-change').click(function (e) {
            var $id = $(this).data('id');
            var $status = $(this).data('value');
            var sendUrl = '{{ route('sales.allocation.status.change', ['allocation' => 'ID', 'status' => 'STATUS']) }}';
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4aba45',
                confirmButtonText: 'Submit'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: sendUrl.replace('ID', $id).replace('STATUS', $status),
                        type: 'PATCH',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Status Changed!',
                                'Allocation status changed successfully!',
                                'success'
                            ).then(function (confirm) {
                                if (confirm) {
                                    window.location.reload()
                                }
                            });
                        }
                    });
                }
            });
        })

        $('.allow-mobile-login').click(function (e) {
            var $id = $(this).data('id');
            var sendUrl = '{{ route('sales.allocation.allow.mobile.login', ['allocation' => 'ID']) }}';
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4aba45',
                confirmButtonText: 'Allow'
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: sendUrl.replace('ID', $id),
                        type: 'PATCH',
                        data: {'_token': '{{ csrf_token() }}'},
                        success: function (result) {
                            swal(
                                'Access!',
                                'Mobile login allowed successfully!',
                                'success'
                            ).then(function (confirm) {
                                if (confirm) {
                                    window.location.reload()
                                }
                            });
                        }
                    });
                }
            });
        })
    </script>
    <?php
    $stockHistories = $allocation->stockHistories;
    $salesHandover = $allocation->salesHandover;
    $handOverStock = collect();
    if ($salesHandover) {
        $handOverStock = $salesHandover->stockHistories;
    }
    $productHistories = $stockHistories->merge($handOverStock)->sortByDesc('id')->load(['transable', 'stock.product']);
    $productHistories = $productHistories->transform(function ($productHistory) {
        $productHistory->createdAt = date("F j, Y, g:i a", strtotime($productHistory->created_at));
        return $productHistory;
    });
    $productHistories = array_values($productHistories->toArray())
    ?>
    <script>
        app.controller('AllocationController', ['$scope', '$http', function ($scope, $http) {
            $scope.productHistories = @json($productHistories);
            $scope.customerSearch = '';
            $scope.productSearch = '';
            $scope.creditOrderSearch = '';
            $scope.products = [];
            $scope.customers = [];
            $scope.totalData = [];

            $scope.mapCustomers = @json($customers->toArray());

            $scope.getCustomers = function () {
                var customerRoute = '{{ route('sales.allocation.get.all.customer', [$allocation]) }}';
                $http.get(customerRoute + '?search=' + $scope.customerSearch).then(function (response) {
                    $scope.customers = response.data;
                })
            };
            $scope.getCustomers();

            $scope.getProducts = function () {
                var productRoute = '{{ route('sales.allocation.get.all.product', [$allocation]) }}';
                $http.get(productRoute + '?search=' + $scope.productSearch).then(function (response) {
                    $scope.products = response.data;
                })
            };
            $scope.getProducts();

            $scope.getCreditOrders = function () {
                var creditOrderRoute = '{{ route('sales.allocation.get.credit.order', [$allocation]) }}';
                $http.get(creditOrderRoute + '?search=' + $scope.creditOrderSearch).then(function (response) {
                    $scope.orders = response.data.orders;
                    $scope.totalData = response.data.total;
                })
            };
            $scope.getCreditOrders();

            $scope.getTotal = function (object, get) {
                return sum(_.pluck(object, get));
            };
            $scope.distanceRoute = '{{ route('sales.distance.order.update', ['ID']) }}';
            $scope.getDistance = function (order) {
                if (!order.distance && order.gps_lat && order.gps_long && order.customer && order.customer.gps_lat && order.customer.gps_long) {
                    var distance = getDistance(order.gps_lat, order.gps_long, order.customer.gps_lat, order.customer.gps_long, $scope.distanceRoute.replace('ID', order.id));
                    var name = '#distance-' + order.id;
                    $(name).text(distance.toFixed(2) + 'KM')
                }
            };

            {{--$scope.orders = @json(allocationOrders($allocation));--}}
            {{--$(document).ready(function () {--}}
                {{--$.each($scope.orders, function (k, order) {--}}
                    {{--$scope.getDistance(order);--}}
                {{--})--}}
            {{--});--}}
            $scope.customerRoute = '{{ route('sales.distance.customer.update', ['ID']) }}';
            $scope.route = '{{ route('map.index') }}';


            /** load customer locations */

            $scope.icons = {
                green: '{{ asset('images/icon/customer_green.png') }}',
                red: '{{ asset('images/icon/customer_red.png') }}',
                orange: '{{ asset('images/icon/customer_orange.png') }}'
            };

            var markers = [];
            var map;
            $(document).ready(function () {
                initMap();
                $scope.init();
            });

            $scope.points = {};
            $scope.init = function () {
                $scope.coords = [];
                if ($scope.mapCustomers) {
                    clearMarkers();
                    $.each($scope.mapCustomers, function (key, value) {

                        if (value.is_visited === 'Yes') {
                            icon = $scope.icons.green;
                        }else{
                            icon = $scope.icons.red;
                        }

                        if (!$scope.points.hasOwnProperty(value.customer.id) && value.customer.gps_lat && value.customer.gps_long) {
                            addMarkerWithTimeout(getLngLat(value.customer.gps_lat, value.customer.gps_long), key * 200, icon, value);
                        }
                        $scope.points[value.customer.id] = getLngLat(value.customer.gps_lat, value.customer.gps_long);
                    });
                }
            };

            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 11,
                    center: {lat: 9.720663, lng: 80.148921}
                });
            }

            function getLngLat(lat, lng) {
                return new google.maps.LatLng(lat, lng);
            }

            var infoWindows = [];

            function addMarkerWithTimeout(position, timeout, icon, customer) {
                var marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    animation: google.maps.Animation.DROP,
                    icon: icon
                });

                markers.push(marker);
                var contentString = '';
                contentString = getContent(contentString, customer);
                addInfo(marker, contentString);
                marker.addListener('click', function () {
                    infoWindows[markers.indexOf(marker)].open(map, marker);
                });
            }

            function clearMarkers() {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                }
                markers = [];
            }

            function addInfo(marker, contentString) {
                if (contentString) {
                    infoWindows[markers.indexOf(marker)] = new google.maps.InfoWindow({
                        content: contentString
                    });
                }
            }

            function getContent(contentString, customer) {
                contentString = '<div style="width: 350px;" id="content">' +
                    '<h1 id="firstHeading" style="font-size: 16px !important;font-weight: 600;line-height: 25px;" class="firstHeading"> ' + customer.customer.display_name + '</h1>' +
                    '<div id="bodyContent">' +
                    '<span style="font-weight: 500;">Visited?: </span>' + customer.is_visited + '<br />' +
                    '<span style="font-weight: 500;">Sales: <b style="color: blue;">' + customer.sales  + ' </b></span> <br />' +
                    '<span style="font-weight: 500;">Received: <b style="color: green;">' + customer.received + ' </b></span> <br />' +
                    '<span style="font-weight: 500;">Balance: <b style="color: orange;">' + customer.balance + ' </b></span> <br />' +
                    '<p style="text-align: right; font-weight: 500; border-top: #59c6da 1px solid; margin-bottom: 0.1rem !important; margin-top: 0.5rem !important;"><a target="_blank" href="/sales/customer/'+ customer.customer_id +'"> View Customer </a></p>' +
                    '</div>' +
                    '</div>';
                return contentString;
            }
            /** END - load customer locations */

        }]).directive('customerDirective', function () {
            return function (scope, element, attrs) {
                if (!scope.customer.distance && scope.customer.gps_lat && scope.customer.gps_long && scope.customer.customer.gps_lat && scope.customer.customer.gps_long) {
                    scope.customer.distance = getDistance(scope.customer.gps_lat, scope.customer.gps_long, scope.customer.customer.gps_lat, scope.customer.customer.gps_long, scope.customerRoute.replace('ID', scope.customer.id));
                }
                if (scope.customer.distance) {
                    var info = {
                        heading: scope.customer.customer.display_name,
                        'code': scope.customer.customer.tamil_name
                    };
                    var endInfo = {
                        heading: scope.customer.customer.display_name,
                        code: scope.customer.customer.tamil_name,
                        rep: scope.customer.daily_sale.rep.name
                    };
                    var routeParam = {
                        startLat: scope.customer.customer.gps_lat,
                        startLng: scope.customer.customer.gps_long,
                        startInfo: JSON.stringify(info),
                        endLat: scope.customer.gps_lat,
                        endLng: scope.customer.gps_long,
                        endInfo: JSON.stringify(endInfo),
                    };

                    scope.customer.route = scope.route + '?' + $.param(routeParam);
                }
            };
        });
    </script>
    <script>
        $('.scrollable-widget').slimScroll({
            height: '350px'
        });
    </script>
@endsection
