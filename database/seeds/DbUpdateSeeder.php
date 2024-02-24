<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DbUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::table('routes', function (Blueprint $table) {
            if (!Schema::hasColumn('routes', 'start_point')) {
                $table->string('start_point')->nullable();
            }
            if (!Schema::hasColumn('routes', 'end_point')) {
                $table->string('end_point')->nullable();
            }
            if (!Schema::hasColumn('routes', 'way_points')) {
                $table->longText('way_points')->nullable();
            }
        });

        Schema::table('bill_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('bill_payments', 'purchase_order_id')) {
                $table->unsignedInteger('purchase_order_id')->index()->nullable();
                $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            }
        });

        /** bill payments table change - by kaji - 2018-04-10 */
        Schema::table('bill_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('bill_payments', 'paid_through')) {
                $table->unsignedInteger('paid_through')->index()->nullable();
                $table->foreign('paid_through')->references('id')->on('accounts');
            }
        });
        /** end */

        /** invoice payments table change - by kaji - 2018-04-10 */
        Schema::table('invoice_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_payments', 'deposited_to')) {
                $table->unsignedInteger('deposited_to')->index()->nullable();
                $table->foreign('deposited_to')->references('id')->on('accounts');
            }

            if (!Schema::hasColumn('invoice_payments', 'sales_location_id')) {
                $table->unsignedInteger('sales_location_id')->index()->nullable();
                $table->foreign('sales_location_id')->references('id')->on('sales_locations');
            }
        });
        /** end */

        /** transactions table change - by kaji - 2018-04-10 */
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'code')) {
                $table->string('code', 50)->unique();
            }
            if (!Schema::hasColumn('transactions', 'action')) {
                $table->string('action', 50)->nullable();
            }
        });
        /** end */

        /** products table change - by kaji - 2018-04-16 */
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'retail_price')) {
                $table->double('retail_price')->nullable();
            }
            if (!Schema::hasColumn('products', 'distribution_price')) {
                $table->double('distribution_price')->nullable();
            }
            if (!Schema::hasColumn('products', 'expense_account')) {
                $table->unsignedInteger('expense_account')->index()->nullable();
                $table->foreign('expense_account')->references('id')->on('accounts');
            }
            if (!Schema::hasColumn('products', 'income_account')) {
                $table->unsignedInteger('income_account')->index()->nullable();
                $table->foreign('income_account')->references('id')->on('accounts');
            }
            if (!Schema::hasColumn('products', 'inventory_account')) {
                $table->unsignedInteger('inventory_account')->index()->nullable();
                $table->foreign('inventory_account')->references('id')->on('accounts');
            }
            if (!Schema::hasColumn('products', 'product_image')) {
                $table->string('product_image')->nullable();
            }
            if (!Schema::hasColumn('products', 'tamil_name')) {
                $table->string('tamil_name', 100)->nullable();
            }
            if (!Schema::hasColumn('products', 'is_expirable')) {
                $table->enum('is_expirable', ['Yes', 'No'])->default('Yes');
            }
            if (!Schema::hasColumn('products', 'opening_cost')) {
                $table->double('opening_cost')->nullable();
            }
            if (!Schema::hasColumn('products', 'opening_cost_at')) {
                $table->date('opening_cost_at')->nullable();
            }
            if (!Schema::hasColumn('products', 'opening_qty')) {
                $table->unsignedInteger('opening_qty')->nullable();
            }
            if (!Schema::hasColumn('products', 'opening_qty_at')) {
                $table->date('opening_qty_at')->nullable();
            }
            if (!Schema::hasColumn('products', 'barcode_number')) {
                $table->unsignedBigInteger('barcode_number')->nullable();
            }
        });
        /** end */
        Schema::table('sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_orders', 'rep_id')) {
                $table->unsignedInteger('rep_id')->index()->nullable();
                $table->foreign('rep_id')->references('id')->on('reps');
            }
            if (!Schema::hasColumn('sales_orders', 'sales_type')) {
                $table->enum('sales_type', ['Retail', 'Wholesale', 'Distribution'])->default('Retail');
            }
            if (!Schema::hasColumn('sales_orders', 'price_book_id')) {
                $table->unsignedInteger('price_book_id')->index()->nullable();
                $table->foreign('price_book_id')->references('id')->on('price_books');
            }
            if (!Schema::hasColumn('sales_orders', 'ref')) {
                $table->string('ref', 50)->nullable();
            }
            if (!Schema::hasColumn('sales_orders', 'sales_location_id')) {
                $table->unsignedInteger('sales_location_id')->index()->nullable();
                $table->foreign('sales_location_id')->references('id')->on('sales_locations');
            }
            if (!Schema::hasColumn('sales_orders', 'gps_lat')) {
                $table->string('gps_lat', 100)->nullable();
            }
            if (!Schema::hasColumn('sales_orders', 'gps_long')) {
                $table->string('gps_long', 100)->nullable();
            }
            if (!Schema::hasColumn('sales_orders', 'is_credit_sales')) {
                $table->enum('is_credit_sales', ['Yes', 'No'])->default('No');
            }
            if (!Schema::hasColumn('sales_orders', 'is_order_printed')) {
                $table->enum('is_order_printed', ['Yes', 'No'])->default('No');
            }
            if (!Schema::hasColumn('sales_orders', 'uuid')) {
                $table->string('uuid')->nullable();
            }
        });

        Schema::table('product_sales_order', function (Blueprint $table) {
            if (!Schema::hasColumn('product_sales_order', 'unit_type_id')) {
                $table->unsignedInteger('unit_type_id')->index()->nullable();
                $table->foreign('unit_type_id')->references('id')->on('unit_types');
            }
            if (!Schema::hasColumn('product_sales_order', 'price_book_id')) {
                $table->unsignedInteger('price_book_id')->index()->nullable();
                $table->foreign('price_book_id')->references('id')->on('price_books');
            }
            if (!Schema::hasColumn('product_sales_order', 'is_vehicle')) {
                $table->enum('is_vehicle', ['Yes', 'No'])->default('No');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->unsignedInteger('category_id')->index()->nullable();
                $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
            }

        });

        Schema::table('product_sales_inquiry', function (Blueprint $table) {
            if (!Schema::hasColumn('product_sales_inquiry', 'notes')) {
                $table->text('notes')->index()->nullable();
            }
        });

        Schema::table('sales_inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_inquiries', 'company_id')) {
                $table->unsignedInteger('company_id')->nullable();
                $table->foreign('company_id')->references('id')->on('companies');
            }
        });

        Schema::table('bill_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('bill_payments', 'credit_id')) {
                $table->unsignedInteger('credit_id')->index()->nullable();
                $table->foreign('credit_id')->references('id')->on('supplier_credits');
            }
        });

        Schema::table('invoice_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_payments', 'credit_id')) {
                $table->unsignedInteger('credit_id')->index()->nullable();
                $table->foreign('credit_id')->references('id')->on('customer_credits');
            }
        });

        Schema::table('expense_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('expense_reports', 'business_type_id')) {
                $table->unsignedInteger('business_type_id')->index();
                $table->foreign('business_type_id')->references('id')->on('business_types');
            }

            if (!Schema::hasColumn('expense_reports', 'submitted_by')) {
                $table->unsignedInteger('submitted_by')->index()->nullable();
                $table->foreign('submitted_by')->references('id')->on('users');
            }

            if (!Schema::hasColumn('expense_reports', 'submitted_on')) {
                $table->date('submitted_on')->nullable();
            }
        });

        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'report_id')) {
                $table->unsignedInteger('report_id')->index()->nullable();
                $table->foreign('report_id')->references('id')->on('expense_reports');
            }
            if (!Schema::hasColumn('expenses', 'payment_mode')) {
                $table->enum('payment_mode', ['Cash', 'Cheque', 'Direct Deposit', 'Credit Card'])->default('Cash');
            }
            /** if payment mode is Cheque */
            if (!Schema::hasColumn('expenses', 'cheque_no')) {
                $table->string('cheque_no', 50)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'cheque_date')) {
                $table->date('cheque_date')->nullable();
            }
            /** if payment mode is Direct Deposit */
            if (!Schema::hasColumn('expenses', 'account_no')) {
                $table->string('account_no', 50)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'deposited_date')) {
                $table->string('deposited_date', 50)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'bank_id')) {
                $table->unsignedInteger('bank_id')->index()->nullable();
                $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
            }
            if (!Schema::hasColumn('expenses', 'gps_lat')) {
                $table->string('gps_lat', 100)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'gps_long')) {
                $table->string('gps_long', 100)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'liter')) {
                $table->double('liter', 20)->nullable()->default(0);
            }
            if (!Schema::hasColumn('expenses', 'odometer')) {
                $table->double('odometer', 20)->nullable()->default(0);
            }
            if (!Schema::hasColumn('expenses', 'sales_expense_id')) {
                $table->unsignedInteger('sales_expense_id')->index()->nullable();
                $table->foreign('sales_expense_id')->references('id')->on('sales_expenses')->onDelete('cascade');
            }
            if (!Schema::hasColumn('expenses', 'type_id')) {
                $table->unsignedInteger('type_id')->index()->nullable();
                $table->foreign('type_id')->references('id')->on('expense_types');
            }
        });

        Schema::table('estimates', function (Blueprint $table) {
            if (!Schema::hasColumn('estimates', 'converted_type')) {
                $table->string('converted_type')->nullable();
            }
            if (!Schema::hasColumn('estimates', 'converted_id')) {
                $table->string('converted_id')->nullable();
            }
        });

        Schema::table('staffable', function (Blueprint $table) {
            if (!Schema::hasColumn('staffable', 'is_default')) {
                $table->enum('is_default', ['Yes', 'No'])->default('No');
            }
        });

        Schema::table('daily_sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sale_items', 'store_id')) {
                $table->unsignedInteger('store_id');
                $table->foreign('store_id')->references('id')->on('stores');
            }
        });

        /** invoice payments table change - by kaji - 2018-05-22 */
        Schema::table('invoice_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_payments', 'refunded_from')) {
                $table->unsignedInteger('refunded_from')->index()->nullable();
                $table->foreign('refunded_from')->references('id')->on('accounts');
            }
        });
        /** end */

        Schema::table('daily_sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sale_items', 'sold_qty')) {
                $table->double('sold_qty')->nullable();
            }
            if (!Schema::hasColumn('daily_sale_items', 'restored_qty')) {
                $table->double('restored_qty')->nullable();
            }
            if (!Schema::hasColumn('daily_sale_items', 'cf_qty')) {
                $table->double('cf_qty')->nullable();
            }
        });

        Schema::table('stock_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_histories', 'transable_id')) {
                $table->unsignedInteger('transable_id')->index()->nullable();
            }
            if (!Schema::hasColumn('stock_histories', 'transable_type')) {
                $table->string('transable_type')->nullable();
            }
        });

        Schema::table('daily_sale_customers', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sale_customers', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('daily_sale_customers', 'is_visited')) {
                $table->enum('is_visited', ['Yes', 'No'])->default('No');
            }
            if (!Schema::hasColumn('daily_sale_customers', 'reason')) {
                $table->text('reason')->nullable();
            }
            if (!Schema::hasColumn('daily_sale_customers', 'gps_lat')) {
                $table->string('gps_lat', 100)->nullable();
            }
            if (!Schema::hasColumn('daily_sale_customers', 'gps_long')) {
                $table->string('gps_long', 100)->nullable();
            }
        });

        Schema::table('daily_sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sale_items', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('daily_sales', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sales', 'driver_id')) {
                $table->unsignedInteger('driver_id')->nullable();
                $table->foreign('driver_id')->references('id')->on('staff');
            }

            if (!Schema::hasColumn('daily_sales', 'labour_id')) {
                $table->string('labour_id')->nullable();
            }
            if (!Schema::hasColumn('daily_sales', 'allowance')) {
                $table->double('allowance')->nullable();
            }
            if (!Schema::hasColumn('daily_sales', 'is_logged_in')) {
                $table->enum('is_logged_in', ['Yes', 'No'])->default('No')->nullable();
            }
            if (!Schema::hasColumn('daily_sales', 'is_logged_out')) {
                $table->enum('is_logged_out', ['Yes', 'No'])->default('No')->nullable();
            }
            if (!Schema::hasColumn('daily_sales', 'logged_in_at')) {
                $table->timestampTz('logged_in_at')->nullable();
            }
            if (!Schema::hasColumn('daily_sales', 'logged_out_at')) {
                $table->timestampTz('logged_out_at')->nullable();
            }
            if (!Schema::hasColumn('daily_sales', 'nxt_day_al_route')) {
                $table->unsignedInteger('nxt_day_al_route')->index()->nullable();
                $table->foreign('nxt_day_al_route')->references('id')->on('routes');
            }
        });

        Schema::table('cheque_in_hands', function (Blueprint $table) {
            if (!Schema::hasColumn('cheque_in_hands', 'business_type_id')) {
                $table->unsignedInteger('business_type_id')->nullable();
                $table->foreign('business_type_id')->references('id')->on('business_types')->onDelete('cascade');
            }

            if (!Schema::hasColumn('cheque_in_hands', 'settled')) {
                $table->enum('settled', ['Yes', 'No'])->default('No');
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'supplier_id')) {
                $table->unsignedInteger('supplier_id')->index()->nullable();
                $table->foreign('supplier_id')->references('id')->on('suppliers');
            }
            if (!Schema::hasColumn('transactions', 'customer_id')) {
                $table->unsignedInteger('customer_id')->index()->nullable();
                $table->foreign('customer_id')->references('id')->on('customers');
            }
            if (!Schema::hasColumn('transactions', 'type')) {
                $table->enum('type', ['Deposit', 'Withdrawal']);
            }
        });

        Schema::table('sales_locations', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_locations', 'vehicle_id')) {
                $table->unsignedInteger('vehicle_id')->index()->nullable();
                $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'prefix')) {
                $table->string('prefix')->nullable();
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'ref')) {
                $table->string('ref', 50);
            }

            if (!Schema::hasColumn('invoices', 'sales_location_id')) {
                $table->unsignedInteger('sales_location_id')->index()->nullable();
                $table->foreign('sales_location_id')->references('id')->on('sales_locations');
            }

            if (!Schema::hasColumn('invoices', 'uuid')) {
                $table->string('uuid')->nullable();
            }
        });

        /** sales handovers table change - by kaji - 2018-06-06 */
        Schema::table('sales_handovers', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_handovers', 'is_cashier_approved')) {
                $table->enum('is_cashier_approved', ['Yes', 'No'])->default('No');
            }

            if (!Schema::hasColumn('sales_handovers', 'cashier_id')) {
                $table->unsignedInteger('cashier_id')->index()->nullable();
                $table->foreign('cashier_id')->references('id')->on('users');
            }

            if (!Schema::hasColumn('sales_handovers', 'is_sk_approved')) {
                $table->enum('is_sk_approved', ['Yes', 'No'])->default('No');
            }

            if (!Schema::hasColumn('sales_handovers', 'sk_id')) {
                $table->unsignedInteger('sk_id')->index()->nullable();
                $table->foreign('sk_id')->references('id')->on('users');
            }
        });
        /** end */

        Schema::table('sales_expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_expenses', 'gps_lat')) {
                $table->string('gps_lat', 100)->nullable();
            }
            if (!Schema::hasColumn('sales_expenses', 'gps_long')) {
                $table->string('gps_long', 100)->nullable();
            }
            if (!Schema::hasColumn('sales_expenses', 'liter')) {
                $table->double('liter', 20)->nullable()->default(0);
            }
            if (!Schema::hasColumn('sales_expenses', 'odometer')) {
                $table->double('odometer', 20)->nullable()->default(0);
            }
            if (!Schema::hasColumn('sales_expenses', 'type_id')) {
                $table->unsignedInteger('type_id')->index()->nullable();
                $table->foreign('type_id')->references('id')->on('expense_types');
            }
        });

        Schema::table('route_product', function (Blueprint $table) {
            if (!Schema::hasColumn('route_product', 'default_qty')) {
                $table->double('default_qty')->default(0)->nullable();
            }
        });

        Schema::table('cheque_in_hands', function (Blueprint $table) {
            if (!Schema::hasColumn('cheque_in_hands', 'shortage')) {
                $table->enum('shortage', ['Damaged', 'Lost', 'Invalid', 'Other'])->nullable();
            }
        });

        /** routes table change - by kaji - 2018-06-08 */
        Schema::table('routes', function (Blueprint $table) {
            if (!Schema::hasColumn('routes', 'company_id')) {
                $table->unsignedInteger('company_id')->index()->nullable();
                $table->foreign('company_id')->references('id')->on('companies');
            }
        });

        /** invoice payments table change - by kaji - 2018-06-11 */
        Schema::table('invoice_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_payments', 'card_holder_name')) {
                $table->string('card_holder_name')->nullable();
            }
            if (!Schema::hasColumn('invoice_payments', 'card_no')) {
                $table->string('card_no')->nullable();
            }
            if (!Schema::hasColumn('invoice_payments', 'expiry_date')) {
                $table->date('expiry_date')->nullable();
            }
            if (!Schema::hasColumn('invoice_payments', 'uuid')) {
                $table->string('uuid')->nullable();
            }
        });

        /** bill payments table change - by kaji - 2018-06-11 */
        Schema::table('bill_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('bill_payments', 'card_holder_name')) {
                $table->string('card_holder_name')->nullable();
            }
            if (!Schema::hasColumn('bill_payments', 'card_no')) {
                $table->string('card_no')->nullable();
            }
            if (!Schema::hasColumn('bill_payments', 'expiry_date')) {
                $table->date('expiry_date')->nullable();
            }
        });

        /** customers table change - by kaji - 2018-06-11 */
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'cl_amount')) {
                $table->double('cl_amount')->default(0)->nullable();
            }
            if (!Schema::hasColumn('customers', 'cl_notify_rate')) {
                $table->double('cl_notify_rate')->default(0)->nullable();
            }
            if (!Schema::hasColumn('customers', 'tamil_name')) {
                $table->string('tamil_name', 100)->nullable();
            }
            if (!Schema::hasColumn('customers', 'opening_balance')) {
                $table->double('opening_balance')->nullable();
            }
            if (!Schema::hasColumn('customers', 'opening_balance_at')) {
                $table->date('opening_balance_at')->nullable();
            }
            if (!Schema::hasColumn('customers', 'opening_balance_type')) {
                $table->enum('opening_balance_type', ['Debit', 'Credit'])->default('Debit');
            }
        });

        /** reps table change - by kaji - 2018-06-11 */
        Schema::table('reps', function (Blueprint $table) {
            if (!Schema::hasColumn('reps', 'cl_amount')) {
                $table->double('cl_amount')->default(0)->nullable();
            }
            if (!Schema::hasColumn('reps', 'cl_notify_rate')) {
                $table->double('cl_notify_rate')->default(0)->nullable();
            }
        });

        /** routes table change - by kaji - 2018-06-11 */
        Schema::table('routes', function (Blueprint $table) {
            if (!Schema::hasColumn('routes', 'cl_amount')) {
                $table->double('cl_amount')->default(0)->nullable();
            }
            if (!Schema::hasColumn('routes', 'cl_notify_rate')) {
                $table->double('cl_notify_rate')->default(0)->nullable();
            }
        });

        /** sales orders table change - by kaji - 2018-06-12 */
        Schema::table('sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_orders', 'order_mode')) {
                $table->enum('order_mode', ['Customer', 'Cash'])->default('Customer');
            }
            if (!Schema::hasColumn('sales_orders', 'is_credit_sales')) {
                $table->enum('is_credit_sales', ['Yes', 'No'])->default('No');
            }
        });

        /** sales orders table change - by kaji - 2018-06-13 */
        Schema::table('sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_orders', 'is_order_printed')) {
                $table->enum('is_order_printed', ['Yes', 'No'])->default('No');
            }
        });

        Schema::table('sales_returns', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_returns', 'code')) {
                $table->string('code');
            }
            if (!Schema::hasColumn('sales_returns', 'daily_sale_id')) {
                $table->unsignedInteger('daily_sale_id')->index()->nullable();
                $table->foreign('daily_sale_id')->references('id')->on('daily_sales');

            }
        });

        Schema::table('sales_return_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_return_items', 'returned_amount')) {
                $table->double('returned_amount')->default(0)->nullable();
            }
        });

        /** vehicles table change - by Mano - 2018-06-19 */
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'category')) {
                $table->enum('category', ['General', 'Sales'])->default('General');
            }
            /** vehicles table change - by Mano - 2018-06-20 */

            if (!Schema::hasColumn('vehicles', 'type_of_body')) {
                $table->string('type_of_body')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'seating_capacity')) {
                $table->string('seating_capacity')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'weight')) {
                $table->string('weight')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'gross')) {
                $table->string('gross')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'tyre_size_front')) {
                $table->string('tyre_size_front')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'tyre_size_rear')) {
                $table->string('tyre_size_rear')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'length')) {
                $table->string('length')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'width')) {
                $table->string('width')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'height')) {
                $table->string('height')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'wheel_front')) {
                $table->string('wheel_front')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'wheel_rear')) {
                $table->string('wheel_rear')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'image')) {
                $table->string('image')->nullable();
            }
        });

        /** sales allocation items table change - by kaji - 2018-06-21 */
        Schema::table('daily_sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sale_items', 'replaced_qty')) {
                $table->double('replaced_qty')->nullable();
            }
        });

        /** sales return table change - by kaji - 2018-06-26 */
        Schema::table('sales_returns', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_returns', 'is_printed')) {
                $table->enum('is_printed', ['Yes', 'No'])->default('No');
            }
        });

        /** bill payments table change - by kaji - 2018-06-27 */
        Schema::table('bill_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('bill_payments', 'cheque_type')) {
                $table->enum('cheque_type', ['Own', 'Third Party'])->default('Own');
            }
        });

        /** invoice payments table change - by kaji - 2018-06-27 */
        Schema::table('invoice_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_payments', 'cheque_type')) {
                $table->enum('cheque_type', ['Own', 'Third Party'])->default('Own')->nullable();
            }
        });

        /** sales orders table change - by kaji - 2018-06-27 */
        Schema::table('sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_orders', 'sales_category')) {
                $table->enum('sales_category', ['Office', 'Shop', 'Van']);
            }
        });

        /** cheques in hand table change - by kaji - 2018-06-28 */
        Schema::table('cheque_in_hands', function (Blueprint $table) {
            if (!Schema::hasColumn('cheque_in_hands', 'cheque_type')) {
                $table->enum('cheque_type', ['Own', 'Third Party'])->default('Own')->nullable();
            }
        });

        Schema::table('sales_handovers', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_handovers', 'excess')) {
                $table->double('excess')->default(0)->nullable();
            }
        });

        /** distance field added by Mano 2018/07/13  **/
        Schema::table('daily_sale_customers', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sale_customers', 'distance')) {
                $table->double('distance')->nullable();
            }
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_orders', 'distance')) {
                $table->double('distance')->nullable();
            }
        });

        Schema::table('invoice_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_payments', 'is_cheque_realized')) {
                $table->enum('is_cheque_realized', ['Yes', 'No'])->default('No')->nullable();
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'tfa')) {
                $table->enum('tfa', ['Yes', 'No'])->default('Yes');
            }
            if (!Schema::hasColumn('users', 'tfa_expiry')) {
                $table->datetime('tfa_expiry')->nullable();
            }
        });

        /** daily sale credit orders table change - by kaji - 2018-08-01 */
        Schema::table('daily_sale_credit_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sale_credit_orders', 'added_stage')) {
                $table->enum('added_stage', ['First', 'Later'])->default('First');
            }
        });

        /** daily sale customers table change - by kaji - 2018-08-01 */
        Schema::table('daily_sale_customers', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sale_customers', 'added_stage')) {
                $table->enum('added_stage', ['First', 'Later'])->default('First');
            }
        });

        /** daily sale items table change - by kaji - 2018-08-01 */
        Schema::table('daily_sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sale_items', 'added_stage')) {
                $table->enum('added_stage', ['First', 'Later'])->default('First');
            }
        });

        Schema::table('opening_balance_references', function (Blueprint $table) {
            if (!Schema::hasColumn('opening_balance_references', 'amount')) {
                $table->double('amount')->nullable();
            }
        });

        /** columns to be added in opening table reference  */
        Schema::table('opening_balance_references', function (Blueprint $table) {
            if (!Schema::hasColumn('opening_balance_references', 'reference_type')) {
                $table->enum('reference_type', ['Account', 'Customer', 'Supplier'])->nullable();
            }
            if (!Schema::hasColumn('opening_balance_references', 'invoice_no')) {
                $table->string('invoice_no', 50)->nullable();
            }
            if (!Schema::hasColumn('opening_balance_references', 'invoice_date')) {
                $table->date('invoice_date')->nullable();
            }
            if (!Schema::hasColumn('opening_balance_references', 'invoice_amount')) {
                $table->double('invoice_amount')->nullable();
            }
            if (!Schema::hasColumn('opening_balance_references', 'invoice_due')) {
                $table->string('invoice_due', 50)->nullable();
            }
            if (!Schema::hasColumn('opening_balance_references', 'invoice_due_age')) {
                $table->string('invoice_due_age', 50)->nullable();
            }
            if (!Schema::hasColumn('opening_balance_references', 'bill_no')) {
                $table->string('bill_no', 50)->nullable();
            }
            if (!Schema::hasColumn('opening_balance_references', 'bill_date')) {
                $table->date('bill_date')->nullable();
            }
            if (!Schema::hasColumn('opening_balance_references', 'bill_amount')) {
                $table->double('bill_amount')->nullable();
            }
            if (!Schema::hasColumn('opening_balance_references', 'bill_due')) {
                $table->string('bill_due', 50)->nullable();
            }
            if (!Schema::hasColumn('opening_balance_references', 'bill_due_age')) {
                $table->string('bill_due_age', 50)->nullable();
            }

            if (!Schema::hasColumn('opening_balance_references', 'order_id')) {
                $table->unsignedInteger('order_id')->index()->nullable();
                $table->foreign('order_id')->references('id')->on('sales_orders');
            }
        });

        Schema::table('suppliers', function (Blueprint $table) {
            if (!Schema::hasColumn('suppliers', 'opening_balance')) {
                $table->double('opening_balance')->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'opening_balance_at')) {
                $table->date('opening_balance_at')->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'opening_balance_type')) {
                $table->enum('opening_balance_type', ['Debit', 'Credit'])->default('Debit');
            }
        });

        /**
         * Accounts type table update
         */
        Schema::table('expense_types', function (Blueprint $table) {
            if (!Schema::hasColumn('expense_types', 'is_mobile_enabled')) {
                $table->enum('is_mobile_enabled', ['Yes', 'No'])->default('Yes');
            }
            if (!Schema::hasColumn('expense_types', 'account_id')) {
                $table->unsignedInteger('account_id')->index()->nullable();
                $table->foreign('account_id')->references('id')->on('accounts');
            }
        });

        /** sales order table updates */
        Schema::table('sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_orders', 'route_id')) {
                $table->unsignedInteger('route_id')->index()->nullable();
                $table->foreign('route_id')->references('id')->on('routes');
            }
            if (!Schema::hasColumn('sales_orders', 'location_id')) {
                $table->unsignedInteger('location_id')->index()->nullable();
                $table->foreign('location_id')->references('id')->on('locations');
            }
            if (!Schema::hasColumn('sales_orders', 'is_opining')) {
                $table->enum('is_opining', ['Yes', 'No'])->default('No');
            }
        });

        /** account group table updates by category id */
        Schema::table('account_groups', function (Blueprint $table) {
            if (!Schema::hasColumn('account_groups', 'category_id')) {
                $table->unsignedInteger('category_id')->index()->nullable();
                $table->foreign('category_id')->references('id')->on('account_categories');
            }
        });

        /** users table updates */
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'allowed_non_working_hrs')) {
                $table->enum('allowed_non_working_hrs', ['Yes', 'No'])->default('No');
            }
        });

        Schema::table('sales_handover_shortages', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_handover_shortages', 'approved_by')) {
                $table->unsignedInteger('approved_by')->index()->nullable();
                $table->foreign('approved_by')->references('id')->on('users');
            }
            if (!Schema::hasColumn('sales_handover_shortages', 'approved_at')) {
                $table->date('approved_at')->nullable();
            }
            if (!Schema::hasColumn('sales_handover_shortages', 'rejected_by')) {
                $table->unsignedInteger('rejected_by')->index()->nullable();
                $table->foreign('rejected_by')->references('id')->on('users');
            }
            if (!Schema::hasColumn('sales_handover_shortages', 'rejected_at')) {
                $table->date('rejected_at')->nullable();
            }
            if (!Schema::hasColumn('sales_handover_shortages', 'status')) {
                $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            }
        });

        Schema::table('daily_sales', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sales', 'start_time')) {
                $table->dateTime('start_time')->nullable();
            }

            if (!Schema::hasColumn('daily_sales', 'end_time')) {
                $table->dateTime('end_time')->nullable();
            }
        });

        Schema::table('daily_sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sale_items', 'returned_qty')) {
                $table->double('returned_qty')->nullable();
            }

            if (!Schema::hasColumn('daily_sale_items', 'shortage_qty')) {
                $table->double('shortage_qty')->nullable();
            }

            if (!Schema::hasColumn('daily_sale_items', 'damaged_qty')) {
                $table->double('damaged_qty')->nullable();
            }
        });

        Schema::table('transaction_records', function (Blueprint $table) {
            if (!Schema::hasColumn('transaction_records', 'category')) {
                $table->enum('category', ['Account', 'Customer'])->default('Account');
            }
        });

        /** stocks table updates */
        Schema::table('stock_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_histories', 'production_unit_id')) {
                $table->unsignedInteger('production_unit_id')->index()->nullable();
                $table->foreign('production_unit_id')->references('id')->on('production_units');
            }
            if (!Schema::hasColumn('stock_histories', 'sales_location_id')) {
                $table->unsignedInteger('sales_location_id')->index()->nullable();
                $table->foreign('sales_location_id')->references('id')->on('sales_locations');
            }
            if (!Schema::hasColumn('stock_histories', 'rate')) {
                $table->double('rate')->default(0)->nullable();
            }
            if (!Schema::hasColumn('stock_histories', 'type')) {
                $table->enum('type', ['Purchase', 'Sale', 'Taken', 'Restore'])->nullable();
            }
            if (!Schema::hasColumn('stock_histories', 'store_id')) {
                $table->unsignedInteger('store_id')->index()->nullable();
                $table->foreign('store_id')->references('id')->on('stores');
            }
        });

        /** reps table updated with company id */
        Schema::table('reps', function (Blueprint $table) {
            if (!Schema::hasColumn('reps', 'company_id')) {
                $table->unsignedInteger('company_id')->index()->nullable();
                $table->foreign('company_id')->references('id')->on('companies');
            }
        });

        Schema::table('stocks', function (Blueprint $table) {
            if (!Schema::hasColumn('stocks', 'min_stock_level')) {
                $table->double('min_stock_level')->nullable();
            }
        });

        /** sales return resolutions table updated with order id */
        Schema::table('sales_return_resolutions', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_return_resolutions', 'order_id')) {
                $table->unsignedInteger('order_id')->index()->nullable();
                $table->foreign('order_id')->references('id')->on('sales_orders');
            }
        });

        Schema::table('invoice_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_payments', 'gps_lat')) {
                $table->string('gps_lat', 100)->nullable();
            }
            if (!Schema::hasColumn('invoice_payments', 'gps_long')) {
                $table->string('gps_long', 100)->nullable();
            }
        });

        Schema::table('cheque_in_hands', function (Blueprint $table) {
            if (!Schema::hasColumn('cheque_in_hands', 'customer_id')) {
                $table->unsignedInteger('customer_id')->index()->nullable();
                $table->foreign('customer_id')->references('id')->on('customers');
            }
            if (!Schema::hasColumn('cheque_in_hands', 'daily_sale_id')) {
                $table->unsignedInteger('daily_sale_id')->index()->nullable();
                $table->foreign('daily_sale_id')->references('id')->on('daily_sales');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'packet_price')) {
                $table->double('packet_price')->nullable();
            }
        });

        Schema::table('transfers', function (Blueprint $table) {
            if (!Schema::hasColumn('transfers', 'received_on')) {
                $table->date('received_on')->nullable();
            }
        });

        Schema::table('cheque_in_hands', function (Blueprint $table) {
            if (!Schema::hasColumn('cheque_in_hands', 'is_transferred')) {
                $table->enum('is_transferred', ['Yes', 'No'])->default('No');
            }
        });

        Schema::table('transfers', function (Blueprint $table) {
            if (!Schema::hasColumn('transfers', 'received_amount')) {
                $table->double('received_amount')->default(0)->nullable();
            }
        });

        Schema::table('transfers', function (Blueprint $table) {
            if (!Schema::hasColumn('transfers', 'deposited_receipt')) {
                $table->string('deposited_receipt')->nullable();
            }
            if (!Schema::hasColumn('transfers', 'receipt_uploaded_on')) {
                $table->dateTime('receipt_uploaded_on')->nullable();
            }
            if (!Schema::hasColumn('transfers', 'receipt_uploaded_by')) {
                $table->unsignedInteger('receipt_uploaded_by')->index()->nullable();
                $table->foreign('receipt_uploaded_by')->references('id')->on('users');
            }
        });

        Schema::table('cheque_in_hands', function (Blueprint $table) {
            if (!Schema::hasColumn('cheque_in_hands', 'transferred_from')) {
                $table->unsignedInteger('transferred_from')->index()->nullable();
                $table->foreign('transferred_from')->references('id')->on('accounts');
            }
            if (!Schema::hasColumn('cheque_in_hands', 'transferred_to')) {
                $table->unsignedInteger('transferred_to')->index()->nullable();
                $table->foreign('transferred_to')->references('id')->on('accounts');
            }
        });

        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'display_name')) {
                $table->string('display_name', 100);
            }
        });

        Schema::table('sales_expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_expenses', 'expense_time')) {
                $table->time('expense_time')->nullable();
            }
        });

        Schema::table('daily_sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sale_items', 'excess_qty')) {
                $table->double('excess_qty')->nullable();
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'route_id')) {
                $table->unsignedInteger('route_id')->index()->nullable();
                $table->foreign('route_id')->references('id')->on('routes');
            }
            if (!Schema::hasColumn('invoices', 'is_opening')) {
                $table->enum('is_opening', ['Yes', 'No'])->default('No');
            }
        });

        Schema::table('invoice_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_payments', 'route_id')) {
                $table->unsignedInteger('route_id')->index()->nullable();
                $table->foreign('route_id')->references('id')->on('routes');
            }
            if (!Schema::hasColumn('invoice_payments', 'is_opening')) {
                $table->enum('is_opening', ['Yes', 'No'])->default('No');
            }
        });

        Schema::table('daily_sales', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sales', 'store_id')) {
                $table->unsignedInteger('store_id')->index()->nullable();
                $table->foreign('store_id')->references('id')->on('stores');
            }
        });

        Schema::table('sales_return_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_return_items', 'date')) {
                $table->date('date')->nullable();
            }
            if (!Schema::hasColumn('sales_return_items', 'customer_id')) {
                $table->unsignedInteger('customer_id')->index()->nullable();
                $table->foreign('customer_id')->references('id')->on('customers');
            }
            if (!Schema::hasColumn('sales_return_items', 'company_id')) {
                $table->unsignedInteger('company_id')->index()->nullable();
                $table->foreign('company_id')->references('id')->on('companies');
            }
            if (!Schema::hasColumn('sales_return_items', 'daily_sale_id')) {
                $table->unsignedInteger('daily_sale_id')->index()->nullable();
                $table->foreign('daily_sale_id')->references('id')->on('daily_sales');
            }
        });

        Schema::table('grns', function (Blueprint $table) {
            if (!Schema::hasColumn('grns', 'bill_id')) {
                $table->unsignedInteger('bill_id')->index()->nullable();
                $table->foreign('bill_id')->references('id')->on('bills');
            }
        });

        Schema::table('suppliers', function (Blueprint $table) {
            if (!Schema::hasColumn('suppliers', 'supplierable_id')) {
                $table->unsignedInteger('supplierable_id')->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'supplierable_type')) {
                $table->string('supplierable_type')->nullable();
            }
        });

        Schema::table('grns', function (Blueprint $table) {
            if (!Schema::hasColumn('grns', 'grn_for')) {
                $table->enum('grn_for', ['PUnit', 'Store', 'Shop'])->nullable();
            }
            if (!Schema::hasColumn('grns', 'production_unit_id')) {
                $table->unsignedInteger('production_unit_id')->index()->nullable();
                $table->foreign('production_unit_id')->references('id')->on('production_units');
            }
            if (!Schema::hasColumn('grns', 'shop_id')) {
                $table->unsignedInteger('shop_id')->index()->nullable();
                $table->foreign('shop_id')->references('id')->on('sales_locations');
            }
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_orders', 'received_cash')) {
                $table->double('received_cash')->default(0)->nullable();
            }
            if (!Schema::hasColumn('sales_orders', 'given_change')) {
                $table->double('given_change')->default(0)->nullable();
            }
        });

        Schema::table('sales_returns', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_returns', 'route_id')) {
                $table->unsignedInteger('route_id')->index()->nullable();
                $table->foreign('route_id')->references('id')->on('routes');
            }
            if (!Schema::hasColumn('sales_returns', 'rep_id')) {
                $table->unsignedInteger('rep_id')->index()->nullable();
                $table->foreign('rep_id')->references('id')->on('reps');
            }
        });

        Schema::table('sales_return_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_return_items', 'route_id')) {
                $table->unsignedInteger('route_id')->index()->nullable();
                $table->foreign('route_id')->references('id')->on('routes');
            }
            if (!Schema::hasColumn('sales_return_items', 'rep_id')) {
                $table->unsignedInteger('rep_id')->index()->nullable();
                $table->foreign('rep_id')->references('id')->on('reps');
            }
        });

        Schema::table('daily_sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sale_items', 'actual_stock')) {
                $table->double('actual_stock')->nullable();
            }
        });

        Schema::table('grn_items', function (Blueprint $table) {
            if (!Schema::hasColumn('grn_items', 'received_amount')) {
                $table->double('received_amount')->default(0)->nullable();
            }
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_orders', 'is_sold_qty_deducted')) {
                $table->enum('is_sold_qty_deducted', ['Yes', 'No'])->default('No');
            }
        });

        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'category')) {
                $table->enum('category', ['Route', 'Shop'])->default('Route');
            }
        });

        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'sales_location_id')) {
                $table->unsignedInteger('sales_location_id')->index()->nullable();
                $table->foreign('sales_location_id')->references('id')->on('sales_locations');
            }
        });

        /** cheque payments table change - by kaji - 2019-05-25 */
        Schema::table('cheque_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('cheque_payments', 'is_printed')) {
                $table->enum('is_printed', ['Yes', 'No'])->default('No');
            }
        });

        /** sales handovers table change - by kaji - 2019-05-26 */
        Schema::table('sales_handovers', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_handovers', 'rc_collection')) {
                $table->double('rc_collection')->default(0)->nullable();
            }
            if (!Schema::hasColumn('sales_handovers', 'rc_cash')) {
                $table->double('rc_cash')->default(0)->nullable();
            }
            if (!Schema::hasColumn('sales_handovers', 'rc_cheque')) {
                $table->double('rc_cheque')->default(0)->nullable();
            }
            if (!Schema::hasColumn('sales_handovers', 'rc_deposit')) {
                $table->double('rc_deposit')->default(0)->nullable();
            }
            if (!Schema::hasColumn('sales_handovers', 'rc_card')) {
                $table->double('rc_card')->default(0)->nullable();
            }
            if (!Schema::hasColumn('sales_handovers', 'rc_credit')) {
                $table->double('rc_credit')->default(0)->nullable();
            }
        });

        /** expense table change - by kaji - 2019-06-07 */
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'expense_category')) {
                $table->enum('expense_category', ['Office', 'Shop', 'Van'])->default('Van');
            }
        });

        /** expense table change - by kaji - 2019-06-16 */
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'card_holder_name')) {
                $table->string('card_holder_name')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'card_no')) {
                $table->string('card_no')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'expiry_date')) {
                $table->date('expiry_date')->nullable();
            }
        });

        /** expense table change - by kaji - 2019-06-17 */
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'vehicle_id')) {
                $table->unsignedInteger('vehicle_id')->index()->nullable();
                $table->foreign('vehicle_id')->references('id')->on('vehicles');
            }
        });

        /** stores table change - by kaji - 2019-06-22 */
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'type')) {
                $table->enum('type', ['General', 'Damage', 'Excess', 'Shortage'])->default('General');
            }
        });

        /** shortage stock table change - by kaji - 2019-07-06 */
        Schema::table('stock_shortage_items', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_shortage_items', 'status')) {
                $table->enum('status', ['Drafted','Approved','Rejected'])->default('Drafted');
            }
            if (!Schema::hasColumn('stock_shortage_items', 'approved_by')) {
                $table->unsignedInteger('approved_by')->index()->nullable();
                $table->foreign('approved_by')->references('id')->on('users');
            }
            if (!Schema::hasColumn('stock_shortage_items', 'approved_on')) {
                $table->dateTime('approved_on')->nullable();
            }
        });

        /** excess stock table change - by kaji - 2019-07-06 */
        Schema::table('stock_excess_items', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_excess_items', 'status')) {
                $table->enum('status', ['Drafted','Approved','Rejected'])->default('Drafted');
            }
            if (!Schema::hasColumn('stock_excess_items', 'approved_by')) {
                $table->unsignedInteger('approved_by')->index()->nullable();
                $table->foreign('approved_by')->references('id')->on('users');
            }
            if (!Schema::hasColumn('stock_excess_items', 'approved_on')) {
                $table->dateTime('approved_on')->nullable();
            }
        });

        /** expensestable change - by kaji - 2019-07-16 */
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'month')) {
                $table->string('month')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'installment_period')) {
                $table->double('installment_period')->default(0)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'no_of_days')) {
                $table->double('no_of_days')->default(0)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'what_was_repaired')) {
                $table->string('what_was_repaired')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'changed_item')) {
                $table->string('changed_item')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'repair_expiry_date')) {
                $table->date('repair_expiry_date')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'repairing_shop')) {
                $table->string('repairing_shop')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'labour_charge')) {
                $table->double('labour_charge')->default(0)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'driver_id')) {
                $table->unsignedInteger('driver_id')->index()->nullable();
                $table->foreign('driver_id')->references('id')->on('staff');
            }
            if (!Schema::hasColumn('expenses', 'odo_at_repair')) {
                $table->double('odo_at_repair')->default(0)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'service_station')) {
                $table->string('service_station')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'odo_at_service')) {
                $table->double('odo_at_service')->default(0)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'parking_name')) {
                $table->string('parking_name')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'vehicle_maintenance_type')) {
                $table->string('vehicle_maintenance_type')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'from_date')) {
                $table->date('from_date')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'to_date')) {
                $table->date('to_date')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'no_of_months')) {
                $table->double('no_of_months')->default(0)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'fine_reason')) {
                $table->string('fine_reason')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'from_destination')) {
                $table->string('from_destination')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'to_destination')) {
                $table->string('to_destination')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'no_of_bags')) {
                $table->double('no_of_bags')->default(0)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'account_number')) {
                $table->string('account_number')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'units_reading')) {
                $table->double('units_reading')->default(0)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'machine')) {
                $table->string('machine')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'festival_name')) {
                $table->string('festival_name')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'donated_to')) {
                $table->string('donated_to')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'donated_reason')) {
                $table->string('donated_reason')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'hotel_name')) {
                $table->string('hotel_name')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'bank_number')) {
                $table->string('bank_number')->nullable();
            }
        });

        /** sales commission table change - by kaji - 2019-07-16 */
        Schema::table('sales_commissions', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_commissions', 'debit_account')) {
                $table->unsignedInteger('debit_account')->index()->nullable();
                $table->foreign('debit_account')->references('id')->on('accounts');
            }
            if (!Schema::hasColumn('sales_commissions', 'credit_account')) {
                $table->unsignedInteger('credit_account')->index()->nullable();
                $table->foreign('credit_account')->references('id')->on('accounts');
            }
        });

        /** excess stock table change - by kaji - 2019-07-06 */
        Schema::table('accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('accounts', 'prefix')) {
                $table->enum('prefix', ['Sales','Purchase','SPN','Cash','CIH','General','Staff','Company','Unit','Store','Shop','Commission','Bank'])
                    ->default('General');
            }
        });

        /** excess stock table change - by kaji - 2019-07-06 */
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'expense_mode')) {
                $table->enum('expense_mode', ['Own', 'ForOthers'])->default('Own');
            }
            if (!Schema::hasColumn('expenses', 'branch_id')) {
                $table->unsignedInteger('branch_id')->index()->nullable();
                $table->foreign('branch_id')->references('id')->on('companies');
            }
            if (!Schema::hasColumn('expenses', 'shop_id')) {
                $table->unsignedInteger('shop_id')->index()->nullable();
                $table->foreign('shop_id')->references('id')->on('sales_locations');
            }
            if (!Schema::hasColumn('expenses', 'approval_required')) {
                $table->enum('approval_required', ['Yes', 'No'])->default('No');
            }
            if (!Schema::hasColumn('expenses', 'approved_by')) {
                $table->unsignedInteger('approved_by')->index()->nullable();
                $table->foreign('approved_by')->references('id')->on('users');
            }
            if (!Schema::hasColumn('expenses', 'approved_on')) {
                $table->dateTime('approved_on')->nullable();
            }
        });

        Schema::table('sales_commissions', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_commissions', 'special_commission')) {
                $table->double('special_commission')->default(0)->nullable();
            }
        });

        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'expense_time')) {
                $table->time('expense_time')->nullable();
            }
        });

        Schema::table('grn_items', function (Blueprint $table) {
            if (!Schema::hasColumn('grn_items', 'no_of_bags')) {
                $table->double('no_of_bags')->default(0)->nullable();
            }
        });

        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'storeable_id')) {
                $table->unsignedInteger('storeable_id')->nullable();
            }
            if (!Schema::hasColumn('stores', 'storeable_type')) {
                $table->string('storeable_type')->nullable();
            }
        });

        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'cheque_type')) {
                $table->enum('cheque_type', ['Own', 'Third Party'])->nullable();
            }
        });

        /** sales return items table change */
        Schema::table('sales_return_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_return_items', 'manufacture_date')) {
                $table->date('manufacture_date')->nullable();
            }
            if (!Schema::hasColumn('sales_return_items', 'expiry_date')) {
                $table->date('expiry_date')->nullable();
            }
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'purchase_request_id')) {
                $table->unsignedInteger('purchase_request_id')->index()->nullable();
                $table->foreign('purchase_request_id')->references('id')->on('purchase_requests');
            }
        });

        Schema::table('purchase_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_requests', 'supply_from')) {
                $table->enum('supply_from', ['PUnit', 'Store', 'Outside'])->nullable();
            }
            if (!Schema::hasColumn('purchase_requests', 'supply_store_id')) {
                $table->unsignedInteger('supply_store_id')->index()->nullable();
                $table->foreign('supply_store_id')->references('id')->on('stores');
            }
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'supply_from')) {
                $table->enum('supply_from', ['PUnit', 'Store', 'Outside'])->nullable();
            }
            if (!Schema::hasColumn('purchase_orders', 'supply_store_id')) {
                $table->unsignedInteger('supply_store_id')->index()->nullable();
                $table->foreign('supply_store_id')->references('id')->on('stores');
            }
        });

        Schema::table('expense_cheques', function (Blueprint $table) {
            if (!Schema::hasColumn('expense_cheques', 'expense_payment_id')) {
                $table->unsignedInteger('expense_payment_id')->index()->nullable();
                $table->foreign('expense_payment_id')->references('id')->on('expense_payments');
            }
        });

        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'cl_days')) {
                $table->double('cl_days')->nullable();
            }
        });

        Schema::table('cheque_in_hands', function (Blueprint $table) {
            if (!Schema::hasColumn('cheque_in_hands', 'bounced_date')) {
                $table->date('bounced_date')->nullable();
            }
        });

        Schema::table('cheque_in_hands', function (Blueprint $table) {
            if (!Schema::hasColumn('cheque_in_hands', 'rep_id')) {
                $table->unsignedInteger('rep_id')->index()->nullable();
                $table->foreign('rep_id')->references('id')->on('reps');
            }
        });

    }
}
