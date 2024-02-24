<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4" ng-show="allocations.length">
                <div class="ribbon-wrapper card ">
                    <div class="ribbon ribbon-success">SALES ALLOCATIONS (@{{ allocations.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="allocation in allocations | itemsPerPage:5"
                            pagination-id="allocation_paginate">
                            <td>
                                <a target="_blank" href="/sales/allocation/@{{ allocation.id }}">
                                    @{{ allocation.code }}
                                </a><br>
                                <small class="text-muted">@{{ allocation.from_date }} | @{{
                                    allocation.to_date }}
                                </small>
                            </td>
                            <td class="text-right" ng-class="statusLabelColor(allocation.status)">
                                @{{ allocation.status }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr ng-if="allocations.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="allocation_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
            <div class="col-md-4" ng-show="salesOrders.length">
                <div class="ribbon-wrapper card ">
                    <div class="ribbon ribbon-success">SALES ORDERS (@{{ salesOrders.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="order in salesOrders | itemsPerPage:5"
                            pagination-id="sales_order_paginate">
                            <td>
                                <a target="_blank" href="/sales/order/@{{ order.id }}">
                                    @{{ order.ref }}
                                </a><br>
                                <small ng-class="statusLabelColor(order.status)">
                                    @{{ order.status }} |
                                </small>
                                <small class="text-muted"> @{{ order.order_date }}
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
                                pagination-id="sales_order_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>

            <div class="col-md-4" ng-show="invoices.length">
                <div class="ribbon-wrapper card ">
                    <div class="ribbon ribbon-success">INVOICES (@{{ invoices.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="invoice in invoices | itemsPerPage:5"
                            pagination-id="invoices_paginate">
                            <td>
                                <a target="_blank" href="/sales/invoice/@{{  invoice.id  }}">
                                    @{{ invoice.ref }}
                                </a><br>
                                <small ng-class="statusLabelColor(invoice.status)">
                                    @{{ invoice.status }} |
                                </small>
                                <small class="text-muted"> @{{ invoice.invoice_date }}
                                </small>
                            </td>
                            <td class="text-right">
                                @{{ invoice.amount |number:2 }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr ng-if="invoices.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="invoices_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4" ng-show="invoicePayments.length">
                <div class="ribbon-wrapper card ">
                    <div class="ribbon ribbon-success">INVOICE PAYMENTS (@{{ invoicePayments.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="payment in invoicePayments | itemsPerPage:5"
                            pagination-id="invoice_payment_paginate">
                            <td>
                                <a target="_blank" href="/sales/invoice/@{{payment.invoice.id  }}">
                                    @{{ payment.invoice.ref }}
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
                    <hr ng-if="invoices.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="invoice_payment_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
            <div class="col-md-4" ng-show="customerCredits.length">
                <div class="ribbon-wrapper card ">
                    <div class="ribbon ribbon-success">CUSTOMER CREDITS (@{{ customerCredits.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="credit in customerCredits | itemsPerPage:5"
                            pagination-id="customer_credits_paginate">
                            <td>
                                <a target="_blank" href="/sales/credit/@{{credit.id }}">
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
                    <hr ng-if="customerCredits.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="customer_credits_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
            <div class="col-md-4" ng-show="estimates.length">
                <div class="ribbon-wrapper card ">
                    <div class="ribbon ribbon-success">SALES ESTIMATES (@{{ estimates.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="estimate in estimates | itemsPerPage:5"
                            pagination-id="estimates_paginate">
                            <td>
                                <a target="_blank" href="/sales/estimate/@{{estimate.id }}">
                                    @{{ estimate.estimate_no }}
                                </a><br>
                                <small ng-class="statusLabelColor(estimate.status)">
                                    @{{ estimate.status }} |
                                </small>
                                <small class="text-muted"> @{{ estimate.estimate_date }}
                                </small>
                            </td>
                            <td class="text-right">
                                @{{ estimate.total |number:2 }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr ng-if="estimates.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="estimates_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4" ng-show="inquiries.length">
                <div class="ribbon-wrapper card ">
                    <div class="ribbon ribbon-success">SALES INQUIRIES (@{{ inquiries.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="inquiry in inquiries | itemsPerPage:5"
                            pagination-id="inquiries_paginate">
                            <td>
                                <a target="_blank" href="/sales/inquiry/@{{inquiry.id }}">
                                    @{{ inquiry.code }}
                                </a><br>
                                <small class="text-muted"> @{{ inquiry.inquiry_date }}
                                </small>
                            </td>
                            <td class="text-right" ng-class="statusLabelColor(inquiry.status)">
                                @{{ inquiry.status }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr ng-if="inquiries.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="inquiries_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
            <div class="col-md-4" ng-show="customers.length">
                <div class="ribbon-wrapper card ">
                    <div class="ribbon ribbon-success">CUSTOMERS (@{{ customers.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="customer in customers | itemsPerPage:5"
                            pagination-id="customers_paginate">
                            <td>
                                <a target="_blank" href="/sales/customer/@{{customer.id }}">
                                    @{{ customer.display_name }}
                                </a>
                                <br />
                                <span class="text-muted">
                                    @{{ customer.tamil_name }} |
                                </span>
                                <span class="text-muted">
                                    <b>P:</b> @{{ customer.mobile }} |
                                </span>
                                <span class="text-muted"><b>T:</b> @{{ customer.phone }}</span>
                                <br />
                                <a target="_blank" href="{{ route('sales.order.create') }}?phoneOrder=@{{customer.id }}" class="btn btn-info btn-sm">
                                    <i class="ti-plus"></i> Create Order
                                </a>
                                <a target="_blank" href="/sales/customer/@{{ customer.id }}/ledger" class="btn btn-warning btn-sm">
                                    <i class="ti-book"></i> View Ledger
                                </a>
                                <a ng-if="!customer.opening_balance" target="_blank" href="/sales/customer/@{{customer.id }}/opening" class="btn btn-primary btn-sm">
                                    <i class="ti-money"></i> Add Opening
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr ng-if="customers.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="customers_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>