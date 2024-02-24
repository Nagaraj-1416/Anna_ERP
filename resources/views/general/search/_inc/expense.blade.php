<div class="row">
    {{--  receipts , reports--}}
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4" ng-show="receipts.length">
                <div class="ribbon-wrapper card ">
                    <div class="ribbon ribbon-success">RECEIPTS ( @{{ receipts.length }} )</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="receipt in receipts | itemsPerPage:5"
                            pagination-id="receipts_paginate">
                            <td>
                                <a target="_blank" href="/expense/receipts/@{{ receipt.id }}">
                                    @{{ receipt.expense_no }}
                                </a><br>
                                <small ng-class="statusLabelColor(receipt.status)">
                                    @{{ receipt.status }} |
                                </small>
                                <small class="text-muted"> @{{ receipt.expense_date }}
                                </small>
                            </td>
                            <td class="text-right">
                                @{{ receipt.amount |number:2 }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr ng-if="receipts.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="receipts_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
            <div class="col-md-4" ng-show="reports.length">
                <div class="ribbon-wrapper card ">
                    <div class="ribbon ribbon-success">REPORTS (@{{ reports.length }})</div>
                    <table class="table custom-table m-t-10">
                        <tbody>
                        <tr dir-paginate="report in reports | itemsPerPage:5"
                            pagination-id="reports_paginate">
                            <td>
                                <a target="_blank" href="/purchase/reports/@{{  report.id  }}">
                                    @{{ report.report_no }}
                                </a><br>
                                <small ng-class="statusLabelColor(report.status)">
                                    @{{ report.status }} |
                                </small>
                                <small class="text-muted">
                                    @{{ report.report_from }} <b>to</b>
                                </small>
                                <small class="text-muted"> @{{ report.report_to }}
                                </small>
                            </td>
                            <td class="text-right">
                                @{{ report.amount | number:2 }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr ng-if="reports.length > 5">
                    <div class="pull-right">
                        <dir-pagination-controls
                                pagination-id="reports_paginate"></dir-pagination-controls>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>