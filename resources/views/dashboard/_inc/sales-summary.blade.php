<!-- Today Sales Summary -->
<div class="card bg-light-success border-default">
    <div class="card-body">
        <div class="d-flex no-block">
            <h3 class="card-title text-megna">Today's Sales Summary</h3>
            <div class="ml-auto"></div>
        </div>
        <h6 class="card-subtitle">Sales collection stats for <span class="text-purple">{{ carbon()->now()->format('F j, Y') }}</span></h6>
        <div class="ribbon-wrapper card m-t-15">
            <div class="ribbon ribbon-success">Collection from Today's Orders</div>
            <div class="table-responsive">
                <table class="ui celled structured table collapse-table">
                    <thead>
                    <tr>
                        <th>Company</th>
                        <th class="text-right">Total Sales</th>
                        <th class="text-right">Total Cash</th>
                        <th class="text-right">Total Cheque</th>
                        <th class="text-right">Total Deposit</th>
                        <th class="text-right">Total Card</th>
                        <th class="text-right">Total Received</th>
                        <th class="text-right">Total Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($todaySales as $sales)
                        <tr class="parent-row">
                            <td> {{ array_get($sales, 'company_name', 'None') }}</td>
                            <td class="text-right text-blue">{{ array_get($sales, 'total_sales', 0.00) }}</td>
                            <td class="text-right">{{ array_get($sales, 'total_cash', 0.00) }}</td>
                            <td class="text-right">{{ array_get($sales, 'total_cheque', 0.00) }}</td>
                            <td class="text-right">{{ array_get($sales, 'total_deposit', 0.00) }}</td>
                            <td class="text-right">{{ array_get($sales, 'total_card', 0.00) }}</td>
                            <td class="text-right text-green">{{ array_get($sales, 'total_paid', 0.00) }}</td>
                            <td class="text-right text-warning">{{ array_get($sales, 'total_balance', 0.00) }}</td>
                        </tr>
                        @if(count($sales['users']) == 0 &&count($sales['shops']) == 0)
                            <tr class="child-row">
                                <td colspan="8">
                                    <code>No records found.</code>
                                </td>
                            </tr>
                        @endif
                        @if(count($sales['users']) > 0)
                            <tr class="child-row">
                                <th colspan="9">Sales Rep</th>
                            </tr>
                        @endif
                        @foreach(array_get($sales, 'users', []) as $user)
                            <tr class="child-row">
                                <td>
                                    <img src="{{route('setting.staff.image', [array_get($user, 'staff_id', '')])}}"
                                         alt="user" width="25" class="img-circle">
                                    {{ array_get($user, 'user_name', 'None') }}
                                </td>
                                <td class="text-right text-blue sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''), 'get' => 'totalSales',
                                     'rep' => array_get($user, 'user_id', 'None')]) }}" data-width="1500px">
                                    {{ number_format(array_get($user, 'total_sales', 0.00), 2) }}
                                </td>
                                <td class="text-right text-blue sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''),
                                    'get' => 'payments', 'rep' => array_get($user, 'user_id', 'None'), 'where' => 'Cash']) }}">
                                    {{ number_format(array_get($user, 'total_cash', 0.00), 2) }}</td>
                                <td class="text-right text-blue sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''), 'get' => 'payments', 'rep' => array_get($user, 'user_id', 'None'), 'where' => 'Cheque']) }}">
                                    {{ number_format(array_get($user, 'total_cheque', 0.00), 2) }}</td>
                                <td class="text-right text-blue sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''), 'get' => 'payments', 'rep' => array_get($user, 'user_id', 'None'), 'where' => 'Direct Deposit']) }}">
                                    {{ number_format(array_get($user, 'total_deposit', 0.00), 2) }}</td>
                                <td class="text-right text-blue sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''), 'get' => 'payments', 'rep' => array_get($user, 'user_id', 'None'), 'where' => 'Credit Card']) }}">
                                    {{ number_format(array_get($user, 'total_card', 0.00), 2) }}</td>
                                <td class="text-right text-green">{{ number_format(array_get($user, 'total_paid', 0.00), 2) }}</td>
                                <td class="text-right text-warning">{{ number_format(array_get($user, 'total_balance', 0.00), 2) }}</td>
                            </tr>
                        @endforeach
                        @if(count($sales['shops']) > 0)
                            <tr class="child-row">
                                <th colspan="9">Shops</th>
                            </tr>
                        @endif
                        @foreach(array_get($sales, 'shops', []) as $shops)
                            <tr class="child-row">
                                <td>
                                    (<i class="fa fa-shopping-basket"></i>) {{ array_get($shops, 'shop_name', 'None') }}
                                </td>
                                <td class="text-right text-blue sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''), 'get' => 'totalSales',
                                     'shop' => array_get($shops, 'shop_id', 'None')]) }}" data-width="1500px">
                                    {{ number_format(array_get($shops, 'total_sales', 0.00), 2) }}</td>
                                <td class="text-right sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''),
                                    'get' => 'payments', 'shop' => array_get($shops, 'shop_id', 'None'), 'where' => 'Cash']) }}">
                                    {{ number_format(array_get($shops, 'total_cash', 0.00), 2) }}</td>
                                <td class="text-right sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''),
                                    'get' => 'payments', 'shop' => array_get($shops, 'shop_id', 'None'), 'where' => 'Cheque']) }}">
                                    {{ number_format(array_get($shops, 'total_cheque', 0.00), 2) }}</td>
                                <td class="text-right sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''),
                                    'get' => 'payments', 'shop' => array_get($shops, 'shop_id', 'None'), 'where' => 'Direct Deposit']) }}">
                                    {{ number_format(array_get($shops, 'total_deposit', 0.00), 2) }}</td>
                                <td class="text-right sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''),
                                    'get' => 'payments', 'shop' => array_get($shops, 'shop_id', 'None'), 'where' => 'Credit Card']) }}">
                                    {{ number_format(array_get($shops, 'total_card', 0.00), 2) }}</td>
                                <td class="text-right text-green">{{ number_format(array_get($shops, 'total_paid', 0.00), 2) }}</td>
                                <td class="text-right text-warning">{{ number_format(array_get($shops, 'total_balance', 0.00), 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td class="text-right td-bg-info"><b>TOTAL</b></td>
                        <td class="text-right td-bg-success">
                            <b>{{ number_format(todayCollection()['totalSales'], 2) }}</b></td>
                        <td class="text-right td-bg-success">
                            <b>{{ number_format(todayCollection()['totalCash'], 2) }}</b></td>
                        <td class="text-right td-bg-success">
                            <b>{{ number_format(todayCollection()['totalCheque'], 2) }}</b></td>
                        <td class="text-right td-bg-success">
                            <b>{{ number_format(todayCollection()['totalDeposit'], 2) }}</b></td>
                        <td class="text-right td-bg-success">
                            <b>{{ number_format(todayCollection()['totalCard'], 2) }}</b></td>
                        <td class="text-right td-bg-success">
                            <b>{{ number_format(todayCollection()['totalPaid'], 2) }}</b></td>
                        <td class="text-right td-bg-success">
                            <b>{{ number_format(todayCollection()['totalBalance'], 2) }}</b></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ribbon-wrapper card">
            <div class="ribbon ribbon-primary">Collection from Old Orders</div>
            <div class="table-responsive">
                <table class="ui celled structured table collapse-table">
                    <thead>
                    <tr class="parent-row">
                        <th>Company</th>
                        <th class="text-right">Total Cash</th>
                        <th class="text-right">Total Cheque</th>
                        <th class="text-right">Total Deposit</th>
                        <th class="text-right">Total Card</th>
                        <th class="text-right">Total Received</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($oldSales as $sales)
                        <tr class="parent-row">
                            <td>{{ array_get($sales, 'company_name', 'None') }}</td>
                            <td class="text-right">{{ number_format(array_get($sales, 'total_cash'), 2) }}</td>
                            <td class="text-right">{{ number_format(array_get($sales, 'total_cheque'), 2) }}</td>
                            <td class="text-right">{{ number_format(array_get($sales, 'total_deposit'), 2) }}</td>
                            <td class="text-right">{{ number_format(array_get($sales, 'total_card'), 2) }}</td>
                            <td class="text-right text-green">{{ number_format(array_get($sales, 'total_paid'), 2) }}</td>
                        </tr>
                        @if(count($sales['users']) == 0 && count($sales['shops']) == 0)
                            <tr class="child-row">
                                <td colspan="6">
                                    <code>No records found.</code>
                                </td>
                            </tr>
                        @endif
                        @if(count($sales['users']) > 0)
                            <tr class="child-row">
                                <th colspan="6">Sales Rep</th>
                            </tr>
                        @endif
                        @foreach(array_get($sales, 'users', []) as $user)
                            <tr class="child-row">
                                <td> (<i class="fa fa-users"></i>) {{ array_get($user, 'user_name', 'None') }}</td>
                                <td class="text-right text-blue sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''),
                                    'get' => 'payments', 'rep' => array_get($user, 'user_id', 'None'), 'where' => 'Cash', 'old' => true]) }}">
                                    {{ number_format(array_get($user, 'total_cash'), 2) }}</td>
                                <td class="text-right text-blue sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''),
                                    'get' => 'payments', 'rep' => array_get($user, 'user_id', 'None'), 'where' => 'Cheque', 'old' => true]) }}">
                                    {{ number_format(array_get($user, 'total_cheque'), 2) }}</td>
                                <td class="text-right text-blue sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''),
                                    'get' => 'payments', 'rep' => array_get($user, 'user_id', 'None'), 'where' => 'Direct Deposit', 'old' => true]) }}">
                                    {{ number_format(array_get($user, 'total_deposit'), 2) }}</td>
                                <td class="text-right text-blue sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''),
                                    'get' => 'payments', 'rep' => array_get($user, 'user_id', 'None'), 'where' => 'Credit Card', 'old' => true]) }}">
                                    {{ number_format(array_get($user, 'total_card'), 2) }}</td>
                                <td class="text-right text-green">{{ number_format(array_get($user, 'total_paid'), 2) }}</td>
                            </tr>
                        @endforeach
                        @if(count($sales['users']) > 0)
                            <tr class="child-row">
                                <th colspan="6">Shops</th>
                            </tr>
                        @endif
                        @foreach(array_get($sales, 'shops', []) as $shop)
                            <tr class="child-row">
                                <td>(<i class="fa fa-shopping-basket"></i>) {{ array_get($shop, 'shop_name', 'None') }}
                                </td>
                                <td class="text-right sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''),
                                    'get' => 'payments', 'shop' => array_get($shop, 'shop_id', 'None'), 'where' => 'Cash', 'old' => true]) }}">
                                    {{ number_format(array_get($shop, 'total_cash'), 2) }}</td>
                                <td class="text-right sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''),
                                    'get' => 'payments', 'shop' => array_get($shop, 'shop_id', 'None'), 'where' => 'Cheque', 'old' => true]) }}">
                                    {{ number_format(array_get($shop, 'total_cheque'), 2) }}</td>
                                <td class="text-right sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''),
                                    'get' => 'payments', 'shop' => array_get($shop, 'shop_id', 'None'), 'where' => 'Direct Deposit', 'old' => true]) }}">
                                    {{ number_format(array_get($shop, 'total_deposit'), 2) }}</td>
                                <td class="text-right sidebar-btn"
                                    data-route="{{ route('dashboard.summary.data', ['company' => array_get($sales, 'company_id', ''),
                                    'get' => 'payments', 'shop' => array_get($shop, 'shop_id', 'None'), 'where' => 'Credit Card', 'old' => true]) }}">
                                    {{ number_format(array_get($shop, 'total_card'), 2) }}</td>
                                <td class="text-right text-green">{{ number_format(array_get($shop, 'total_paid'), 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td class="text-right td-bg-info"><b>TOTAL</b></td>
                        <td class="text-right td-bg-success"><b>{{ number_format(oldCollection()['totalCash'], 2) }}</b>
                        </td>
                        <td class="text-right td-bg-success">
                            <b>{{ number_format(oldCollection()['totalCheque'], 2) }}</b></td>
                        <td class="text-right td-bg-success">
                            <b>{{ number_format(oldCollection()['totalDeposit'], 2) }}</b></td>
                        <td class="text-right td-bg-success"><b>{{ number_format(oldCollection()['totalCard'], 2) }}</b>
                        </td>
                        <td class="text-right td-bg-success"><b>{{ number_format(oldCollection()['totalPaid'], 2) }}</b>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="ribbon-wrapper card">
            <div class="ribbon ribbon-warning">Total Collection from Today's and old Orders</div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12"></div>
                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                    <table class="ui celled structured table">
                        <tfoot>
                        <tr>
                            <th class="right aligned"><b>Collection from today's Orders</b></th>
                            <td class="right aligned td-bg-success">
                                <b>{{ number_format(todayCollection()['totalPaid'], 2) }}</b></td>
                        </tr>
                        <tr>
                            <th class="right aligned"><b>Collection from old Orders</b></th>
                            <td class="right aligned td-bg-success">
                                <b>{{ number_format(oldCollection()['totalPaid'], 2) }}</b></td>
                        </tr>
                        <tr>
                            <th class="right aligned"><b>Total Collection</b></th>
                            <td class="right aligned td-bg-info">
                                <b>{{ number_format((oldCollection()['totalPaid'] + todayCollection()['totalPaid']), 2) }}</b>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
