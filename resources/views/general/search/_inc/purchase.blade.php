<div class="row">
    {{--bills ,  credits, Purchaseorders,Billpayments,supplier--}}
    <div class="col-md-12">
        <div class="row" ng-show="Purchaseorders.length">
            <div class="col-md-4" ng-show="Purchaseorders.length">
                <div class="ribbon-wrapper card fixed-height">
                    <div class="ribbon ribbon-success">PURCHASE ORDERS (@{{ Purchaseorders.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="order in Purchaseorders | itemsPerPage:5"
                            pagination-id="purchase_order_paginate">
                            <td>
                                <a target="_blank" href="/purchase/order/@{{ order.id }}">
                                    @{{ order.po_no }}
                                </a><br>
                                <small ng-class="statusLabelColor(order.status)">
                                    @{{ order.status }} |
                                </small>
                                <small class="text-muted"> @{{ order.delivery_date }}
                                </small>
                            </td>
                            <td class="text-right">
                                @{{ order.total |number:2 }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr ng-if="salesOrders.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="purchase_order_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
            <div class="col-md-4" ng-show="bills.length">
                <div class="ribbon-wrapper card fixed-height">
                    <div class="ribbon ribbon-success">BILLS (@{{ bills.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="bill in bills | itemsPerPage:5"
                            pagination-id="bills_paginate">
                            <td>
                                <a target="_blank" href="/purchase/bill/@{{  bill.id  }}">
                                    @{{ bill.bill_no }}
                                </a><br>
                                <small ng-class="statusLabelColor(bill.status)">
                                    @{{ bill.status }} |
                                </small>
                                <small class="text-muted"> @{{ bill.bill_date }}
                                </small>
                            </td>
                            <td class="text-right">
                                @{{ bill.amount |number:2 }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr ng-if="bills.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="bills_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
            <div class="col-md-4" ng-show="billPayments.length">
                <div class="ribbon-wrapper card fixed-height">
                    <div class="ribbon ribbon-success">BILL PAYMENTS (@{{ billPayments.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="payment in billPayments | itemsPerPage:5"
                            pagination-id="bill_payment_paginate">
                            <td>
                                <a target="_blank" href="/purchase/bill/@{{payment.bill.id  }}">
                                    @{{ payment.bill.bill_no }}
                                </a><br>
                                <small ng-class="statusLabelColor(payment.status)">
                                    @{{ payment.status }} |
                                </small>
                                <small class="text-muted"> @{{ payment.payment_date }}
                                </small>
                            </td>
                            <td class="text-right">
                                @{{ payment.payment |number:2 }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr ng-if="bills.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="bill_payment_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4" ng-show="supplierCredits.length">
                <div class="ribbon-wrapper card fixed-height">
                    <div class="ribbon ribbon-success">SUPPLIER CREDITS (@{{ supplierCredits.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="credit in supplierCredits | itemsPerPage:5"
                            pagination-id="supplier_credits_paginate">
                            <td>
                                <a target="_blank" href="/purchase/credit/@{{credit.id }}">
                                    @{{ credit.code }}
                                </a><br>
                                <small ng-class="statusLabelColor(credit.status)">
                                    @{{ credit.status }} |
                                </small>
                                <small class="text-muted"> @{{ credit.date }}
                                </small>
                            </td>
                            <td class="text-right">
                                @{{ credit.amount |number:2 }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr ng-if="supplierCredits.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="supplier_credits_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
            <div class="col-md-4" ng-show="suppliers.length">
                <div class="ribbon-wrapper card fixed-height">
                    <div class="ribbon ribbon-success">SUPPLIERS (@{{ suppliers.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="supplier in suppliers | itemsPerPage:5"
                            pagination-id="suppliers_paginate">
                            <td>
                                <a target="_blank" href="/purchase/supplier/@{{supplier.id }}">
                                    @{{ supplier.code }}
                                </a><br>
                                <small class="text-muted">
                                    @{{ supplier.display_name }} |
                                </small>
                                <small class="text-muted"> @{{ supplier.email }} |</small>
                                <small class="text-muted">
                                    <b>P:</b> @{{ supplier.mobile }} |
                                </small>
                                <small class="text-muted"><b>T:</b> @{{ supplier.phone }}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr ng-if="suppliers.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="suppliers_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>