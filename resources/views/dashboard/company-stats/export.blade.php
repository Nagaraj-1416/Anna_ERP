<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }} | {{ 'Sales Stats' }}</title>
    <meta charset="iso-8859-1">
</head>
<body>
<!-- pdf full panel -->
<div style="font-family: sans-serif;font-size: 12px;" id="companyStats">
    <div>
        <table style="font-family: sans-serif;width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-size: 22px;font-family: sans-serif;">COMPANY STATS</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;font-family: sans-serif;" align="center">
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 13px;">From </span>
                    <span style="font-weight: 500;color: #455a64;font-family: sans-serif;font-size: 13px;">
                        {{  carbon($request['fromDate'])->format('M d, Y') }}
                    </span>
                    <span style="font-weight: 700;color: #455a64;font-family: sans-serif;font-size: 13px;">To </span>
                    <span style="font-weight: 500;color: #455a64;font-family: sans-serif;font-size: 13px;">
                        {{  carbon($request['toDate'])->format('M d, Y') }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        <h4 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>Purchase Details</b></h4>
        @include('dashboard.company-stats.export.purchase')
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        <h4 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>Sales Details</b></h4>
        @include('dashboard.company-stats.export.sales')
    </div>

    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        <h4 style="font-weight: 600;font-family: sans-serif;font-size: 15px;"><b>Expense Details</b></h4>
        @include('dashboard.company-stats.export.expense')
    </div>
    {{--Sales by Customers--}}
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        @include('dashboard.company-stats.export.grouped-table', ['heading' => 'Sales by Customers',
         'baseName' => 'CUSTOMER NAME', 'amountName' => 'SO AMOUNT',
         'arrays' => array_get($sales_by_customer, 'data') , 'total' => array_get($sales_by_customer, 'order_total'), 'get' => 'display_name',
         'relation' => 'orders',  'pluck' => 'total'])
    </div>
    {{--Purchases by Suppliers--}}
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        @include('dashboard.company-stats.export.table', ['heading' => 'Purchases by Suppliers',
         'baseName' => 'SUPPLIER NAME', 'amountName' => 'PO AMOUNT',
         'arrays' => array_get($purchase_by_supplier, 'data') , 'total' => array_get($purchase_by_supplier, 'order_total'), 'get' => 'display_name',
         'relation' => 'orders',  'pluck' => 'total'])
    </div>

    {{--Sales by Rep--}}
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        @include('dashboard.company-stats.export.table', ['heading' => 'Sales by Shop',
         'baseName' => 'SHOP NAME', 'amountName' => 'SO AMOUNT',
         'arrays' => array_get($sales_by_shop, 'data') , 'total' => array_get($sales_by_shop, 'order_total'), 'get' => 'name',
         'relation' => 'orders',  'pluck' => 'total'])
    </div>

    {{--Sales by Rep--}}
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        @include('dashboard.company-stats.export.table', ['heading' => 'Sales by Rep',
         'baseName' => 'REP NAME', 'amountName' => 'SO AMOUNT',
         'arrays' => array_get($sales_by_rep, 'data') , 'total' => array_get($sales_by_rep, 'order_total'), 'get' => 'name',
         'relation' => 'sales_orders',  'pluck' => 'total'])
    </div>
    {{--Sales by Rep--}}
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        @include('dashboard.company-stats.export.table', ['heading' => 'Sales by Rep',
         'baseName' => 'REP NAME', 'amountName' => 'SO AMOUNT',
         'arrays' => array_get($sales_by_rep, 'data') , 'total' => array_get($sales_by_rep, 'order_total'), 'get' => 'name',
         'relation' => 'sales_orders',  'pluck' => 'total'])
    </div>

    {{--Sales by Products--}}
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        @include('dashboard.company-stats.export.table', ['heading' => 'Sales by Products',
         'baseName' => 'PRODUCT NAME', 'amountName' => 'SO AMOUNT',
         'arrays' => array_get($sales_by_products, 'data') , 'total' => array_get($sales_by_products, 'order_total'), 'get' => 'name',
         'relation' => 'sales_orders',  'pluck' => 'total', 'products' => 'sales_orders'])
    </div>

    {{--Purchases by Products--}}
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        @include('dashboard.company-stats.export.table', ['heading' => 'Purchases by Products',
         'baseName' => 'PRODUCT NAME', 'amountName' => 'PO AMOUNT',
         'arrays' => array_get($purchase_by_products, 'data') , 'total' => array_get($purchase_by_products, 'order_total'), 'get' => 'name',
         'relation' => 'purchase_orders',  'pluck' => 'total', 'products' => 'purchase_orders'])
    </div>

    {{--Customers Balance Summary--}}
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        @include('dashboard.company-stats.export.table',
        [
        'heading' => 'Customers Balance Summary',
         'baseName' => 'CUSTOMER NAME',
         'amountName' => 'BALANCE',
         'arrays' => array_get($customer_balance, 'data') ,
         'total' => array_get($customer_balance, 'balance'),
         'get' => 'display_name',
          'balance' => true
         ])
    </div>

    {{--Suppliers Balance Summary--}}
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        @include('dashboard.company-stats.export.table',
        [
        'heading' => 'Suppliers Balance Summary',
         'baseName' => 'SUPPLIER NAME',
         'amountName' => 'BALANCE',
         'arrays' => array_get($supplier_balance, 'data') ,
         'total' => array_get($supplier_balance, 'balance'),
         'get' => 'display_name',
          'balance' => true
         ])
    </div>

    {{--Customers Expenses--}}
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        @include('dashboard.company-stats.export.table',
        [
        'heading' => 'Customers Expenses',
         'baseName' => 'CUSTOMER NAME',
         'amountName' => 'EXPENSE',
         'arrays' => array_get(array_get($expense_data, 'customer', []), 'data'),
         'total' => array_get(array_get($expense_data, 'customer', []), 'total'),
         'get' => 'display_name',
         'relation' => 'expenses',
         'pluck' => 'amount'
         ])
    </div>

    {{--Suppliers Expenses--}}
    <div style="border-top: 1px solid #D0D0D0; margin-top: 10px;"></div>
    <div style="padding-top: 10px;">
        @include('dashboard.company-stats.export.table',
        [
        'heading' => 'Suppliers Expenses',
         'baseName' => 'SUPPLIER NAME',
         'amountName' => 'EXPENSE',
         'arrays' => array_get(array_get($expense_data, 'supplier', []), 'data'),
         'total' => array_get(array_get($expense_data, 'supplier', []), 'total'),
         'get' => 'display_name',
         'relation' => 'expenses',
         'pluck' => 'amount'
         ])
    </div>
</div>
</body>
</html>
