<?php $request = request() ?>
<div class="card">
    <div class="card-body">
        <h3><b>PAYMENTS MADE</b> <span class="pull-right">Total Payments: @{{ payments.length }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Payment details</th>
                    <th>Recorded by</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                    @if($request->is('sales/invoice*'))
                        <th width="20%"></th>
                    @endif
                </tr>
                </thead>
                <tbody>
                <tr ng-show="payments.length" dir-paginate="payment in payments | itemsPerPage:5"
                    pagination-id="payments_paginate">
                    <td>@{{ payment.payment_date }}</td>
                    <td>
                        <span><b>Type: </b>@{{ payment.payment_type }}</span>,
                        <span><b>Mode: </b>@{{ payment.payment_mode }}</span><br/>
                        <span><b>Paid through: </b>@{{ payment.paid_through.name }}</span>
                    </td>
                    <td>@{{ payment.prepared_by.name }}</td>
                    <td>
                        <span ng-class="statusLabelColor(payment.status)">@{{ payment.status }}</span>
                    </td>
                    <td class="text-right">@{{ payment.payment | number:2 }}</td>
                    @if($request->is('sales/invoice*'))
                        <td style="text-align: right;">
                            <div ng-show="!(payment.status === 'Canceled'|| payment.status === 'Refunded')">
                                @can('edit', new \App\BillPayment())
                                    <a href="" class="btn btn-primary btn-sm edit-payment-btn"
                                       data-id="@{{ payment.id }}">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                @endcan
                                @can('delete', new \App\BillPayment())
                                    <a href="" class="btn btn-danger btn-sm delete-payment-btn"
                                       data-id="@{{ payment.id }}">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                @endcan
                            </div>
                            @can('print', new \App\BillPayment())
                                <a target="_blank"
                                   href="@{{ getRoute(bill) }}"
                                   class="btn btn-inverse btn-sm"><i class="fa fa-print"></i></a>
                            @endcan
                            @can('cancel', new \App\BillPayment())
                                <button ng-show="payment.status === 'Paid'"
                                        class="btn btn-danger btn-sm cancel-payment-btn"
                                        data-id="@{{ payment.id }}">
                                    Cancel
                                </button>
                            @endcan
                            @can('refund', new \App\BillPayment())
                                <a ng-show="payment.status === 'Canceled'" href=""
                                   class="btn btn-warning btn-sm refund-payment-btn"
                                   data-id="@{{ payment.id }}">
                                    Refund
                                </a>
                            @endcan
                        </td>
                    @endif
                </tr>
                <tr ng-show="!payments.length">
                    <td>No Payments Found...</td>
                </tr>
                </tbody>
            </table>

            <hr ng-if="payments.length > 5">
            <div class="pull-right">
                <dir-pagination-controls pagination-id="payments_paginate"></dir-pagination-controls>
            </div>
        </div>
    </div>
</div>