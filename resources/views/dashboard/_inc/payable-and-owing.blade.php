<!-- Payable & Owing Summary -->
<div class="card bg-light-warning border-warning">
    <div class="card-body">
        <h3 class="card-title text-warning">Payable & Owing</h3>
        <hr>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th colspan="3">Invoices Payable to You</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1-30 days overdue</td>
                    <td>@{{ getDueInvoiceCount('1-30') }}</td>
                    <td class="text-right">
                        <a href="" ng-show="getDueInvoiceTotal('1-30')" class="sidebar-btn"
                           data-route="{{route('dashboard.over.due.data', [30, 'model' =>'Invoice']) }}">
                            @{{ getDueInvoiceTotal('1-30') | number:2}}</a>
                        <span ng-show="!getDueInvoiceTotal('1-30')">@{{ getDueInvoiceTotal('1-30') | number:2}}</span>
                    </td>
                </tr>
                <tr>
                    <td>31-60 days overdue</td>
                    <td>@{{ getDueInvoiceCount('31-60') }}</td>
                    <td class="text-right">
                        <a data-route="{{route('dashboard.over.due.data', [60, 'model' =>'Invoice']) }}"
                           class="sidebar-btn"
                           ng-show="getDueInvoiceTotal('31-60')"
                           href="">@{{
                            getDueInvoiceTotal('31-60') | number:2}}</a>
                        <span ng-show="!getDueInvoiceTotal('31-60')">@{{ getDueInvoiceTotal('31-60') | number:2}}</span>
                    </td>
                </tr>
                <tr>
                    <td>61-90 days overdue</td>
                    <td>@{{ getDueInvoiceCount('61-90') }}</td>
                    <td class="text-right">
                        <a data-route="{{route('dashboard.over.due.data', [90, 'model' =>'Invoice']) }}"
                           class="sidebar-btn"
                           ng-show="getDueInvoiceTotal('61-90')"
                           href="">@{{
                            getDueInvoiceTotal('61-90') | number:2}}</a>
                        <span ng-show="!getDueInvoiceTotal('61-90')">@{{ getDueInvoiceTotal('61-90') | number:2}}</span>
                    </td>
                </tr>
                <tr>
                    <td>> 90 days overdue</td>
                    <td>@{{ getDueInvoiceCount('91') }}</td>
                    <td class="text-right">
                        <a data-route="{{route('dashboard.over.due.data', ['>90', 'model' => 'Invoice']) }}"
                           class="sidebar-btn"
                           ng-show="getDueInvoiceTotal('91')"
                           href="">@{{
                            getDueInvoiceTotal('91') | number:2}}</a>
                        <span ng-show="!getDueInvoiceTotal('91')">@{{ getDueInvoiceTotal('91') | number:2}}</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th colspan="3">Bills You Owe</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1-30 days overdue</td>
                    <td>@{{ getDueBillCount('1-30') }}</td>
                    <td class="text-right">
                        <a ng-show="getDueBillTotal('1-30')"
                           class="sidebar-btn"
                           data-route="{{route('dashboard.over.due.data', [30, 'model' =>'Bill']) }}"
                           href="">@{{ getDueBillTotal('1-30') |
                            number:2}}</a>
                        <span ng-show="!getDueBillTotal('1-30')">@{{ getDueBillTotal('1-30') | number:2}}</span>
                    </td>
                </tr>
                <tr>
                    <td>31-60 days overdue</td>
                    <td>@{{ getDueBillCount('31-60') }}</td>
                    <td class="text-right">
                        <a ng-show="getDueBillTotal('31-60')"
                           class="sidebar-btn"
                           data-route="{{route('dashboard.over.due.data', [60, 'model' =>'Bill']) }}"
                           href="">@{{ getDueBillTotal('31-60') |
                            number:2}}</a>
                        <span ng-show="!getDueBillTotal('31-60')">@{{ getDueBillTotal('31-60') | number:2}}</span>
                    </td>
                </tr>
                <tr>
                    <td>61-90 days overdue</td>
                    <td>@{{ getDueBillCount('61-90') }}</td>
                    <td class="text-right">
                        <a ng-show="getDueBillTotal('61-90')"
                           class="sidebar-btn"
                           data-route="{{route('dashboard.over.due.data', [90, 'model' =>'Bill']) }}"
                           href="">@{{ getDueBillTotal('61-90') |
                            number:2}}</a>
                        <span ng-show="!getDueBillTotal('61-90')">@{{ getDueBillTotal('61-90') | number:2}}</span>
                    </td>
                </tr>
                <tr>
                    <td>> 90 days overdue</td>
                    <td>@{{ getDueBillCount('91') }}</td>
                    <td class="text-right">
                        <a ng-show="getDueBillTotal('91')"
                           class="sidebar-btn"
                           data-route="{{route('dashboard.over.due.data', ['>90', 'model' =>'Bill']) }}"
                           href="">@{{ getDueBillTotal('91') |
                            number:2}}</a>
                        <span ng-show="!getDueBillTotal('91')">@{{ getDueBillTotal('91') | number:2}}</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>