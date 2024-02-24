<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountType;
use App\ChequeInHand;
use App\Company;
use App\Customer;
use App\DailySale;
use App\DailySaleCustomer;
use App\DailySaleItem;
use App\DailyStock;
use App\DailyStockItem;
use App\Expense;
use App\Invoice;
use App\InvoicePayment;
use App\Jobs\NextDayAllocationCreateJob;
use App\Jobs\OneTime\UpdateExpenseTypeJob;
use App\Jobs\StockUpdateJob;
use App\Jobs\UpdateDailySalesId;
use App\Jobs\UpdateSoldQty;
use App\OpeningBalanceReference;
use App\Product;
use App\ProductionUnit;
use App\Rep;
use App\Repositories\Finance\AccountRepository;
use App\Repositories\Finance\TransactionRepository;
use App\Repositories\Purchase\SupplierRepository;
use App\Repositories\Sales\AllocationRepository;
use App\Repositories\Sales\CustomerRepository;
use App\Repositories\Sales\HandOverRepository;
use App\Repositories\Settings\FaceRecognitionRepository;
use App\Repositories\Settings\StoreRepository;
use App\Route;
use App\SalesExpense;
use App\SalesHandover;
use App\SalesHandoverExcess;
use App\SalesHandoverShortage;
use App\SalesLocation;
use App\SalesOrder;
use App\SalesReturn;
use App\SalesReturnItem;
use App\Staff;
use App\Stock;
use App\StockExcess;
use App\StockExcessItem;
use App\StockHistory;
use App\StockShortage;
use App\StockShortageItem;
use App\Store;
use App\Supplier;
use App\Transaction;
use App\TransactionRecord;
use Jeylabs\AuditLog\Models\AuditLog;
use PhpParser\Node\Expr\New_;

class TestController extends Controller
{
    protected $account;
    protected $allocation;
    protected $customer;
    protected $supplier;
    protected $transaction;
    protected $handOver;
    protected $store;

    public function __construct(AccountRepository $account,
                                AllocationRepository $allocation,
                                CustomerRepository $customer,
                                SupplierRepository $supplier,
                                TransactionRepository $transaction,
                                HandOverRepository $handOver,
                                StoreRepository $store)
    {
        $this->account = $account;
        $this->allocation = $allocation;
        $this->customer = $customer;
        $this->supplier = $supplier;
        $this->transaction = $transaction;
        $this->handOver = $handOver;
        $this->store = $store;
    }

    public function index($method)
    {
        $hasMethod = method_exists($this, $method);
        if (!$hasMethod) {
            return redirect()->route('dashboard');
        }
        $this->$method();
    }

    public function updateDailySalesId()
    {
        UpdateDailySalesId::dispatch();
    }

    public function updateTxNarration()
    {
        $trans = Transaction::get();
        $trans->each(function (Transaction $tran) {

            $narration = '';
            if ($tran->tx_type_id == 27) {
                $invoice = $tran->transactionable;
                if ($invoice && $invoice->customer) {
                    $narration = 'Invoice #' . $invoice->ref . ', created against to ' . $invoice->customer->display_name;
                } else {
                    $narration = 'Invoice #' . $invoice->ref . ', created';
                }

            } else if ($tran->tx_type_id == 19) {
                $payment = $tran->transactionable;
                if ($payment && $payment->customer) {
                    $narration = 'Payment received from ' . $payment->customer->display_name . ' and deposited to ' . $payment->depositedTo->name;
                } else if ($payment) {
                    $narration = 'Payment received and deposited to ' . $payment->depositedTo->name;
                }
            }
            $tran->setAttribute('auto_narration', $narration);
            $tran->save();
        });

        dd('Done');
    }

    public function updateExpenseType()
    {
        UpdateExpenseTypeJob::dispatch();
    }

    public function createAccountForCustomers()
    {
        $customers = Customer::get();
        $customers->each(function (Customer $customer) {
            $this->account->createCustomerAccount($customer);
        });

        dd('Customer accounts created successfully!!!');
    }

    public function createAccountForSuppliers()
    {
        $suppliers = Supplier::get();
        $suppliers->each(function (Supplier $supplier) {
            $this->account->createSupplierAccount($supplier);
        });

        dd('Supplier accounts created successfully!!!');
    }

    public function dummyStock()
    {
        $products = Product::get();
        $products->each(function (Product $product) {

            /** enter stock */
            $stock = new Stock();
            $stock->setAttribute('type', 'Manual');
            $stock->setAttribute('category', 'Main');
            $stock->setAttribute('product_id', $product->getAttribute('id'));
            $stock->setAttribute('store_id', 1);
            $stock->setAttribute('available_stock', 100000);
            $stock->setAttribute('notes', 'Dummy Opening Stock');
            $stock->setAttribute('company_id', 1);
            $stock->save();

            /** enter stock history */
            $history = new StockHistory();
            $history->setAttribute('stock_id', $stock->getAttribute('id'));
            $history->setAttribute('quantity', 100000);
            $history->setAttribute('transaction', 'In');
            $history->setAttribute('trans_date', \carbon()->now()->toDateString());
            $history->setAttribute('trans_description', 'Dummy Opening Stock');
            $history->save();

        });

        dd('Stock and histories created successfully!!!');
    }

    public function shopsAllocation()
    {
        $shops = SalesLocation::where('type', 'Shop')->get();
        $shops->each(function (SalesLocation $shop) {

            /** create sales allocation for shop */
            $this->allocation->createShopAllocation($shop);
        });
        dd('Sales allocation successfully created of shops!!!');
    }

    public function testData()
    {
        $auditLogs = AuditLog::where('subject_type', DailySaleItem::class)->where('created_at', 'LIKE', '%2018-10-17 20%')->orderBy('created_at', 'decs')->get();
        $auditLogs = $auditLogs->filter(function (AuditLog $auditLog) {
            $properties = $auditLog->properties;
            return array_get(array_get($properties, 'attributes'), 'daily_sale_id') == 21;
        });
//        dd($auditLogs);


        $test = DailySaleItem::where('id', 441)->delete();
        dd($test);
    }

    public function removeAllCustomerAccounts()
    {
        $cusAccounts = Account::where('accountable_type', 'App\Customer')->get();
        $cusAccounts->each(function (Account $cusAccount) {
            $cusAccount->delete();
        });
        dd('Customer account deleted successfully!!!');
    }

    public function removeAllSupplierAccounts()
    {
        $cusAccounts = Account::where('accountable_type', 'App\Supplier')->get();
        $cusAccounts->each(function (Account $cusAccount) {
            $cusAccount->delete();
        });
        dd('Supplier account deleted successfully!!!');
    }

    public function changeTxFromBankToCIH()
    {
        $transactions = TransactionRecord::where('account_id', 2)->get();
        $transactions->each(function (TransactionRecord $transaction) {
            $transaction->setAttribute('account_id', 50);
            $transaction->save();
        });
        dd('CIH transaction changed successfully!!!');
    }

    public function generateProductBarcodeNumber()
    {
        $code = 5901234123457;
        foreach (Product::all() as $product) {
            // $product->update(['barcode_number' => generateProductBarcodeNumber($product->type)]);
            $product->update(['barcode_number' => $code]);
            $code++;
        }
        dd('Done');
    }

    /** Temp, need to remove this */
    function updateDailyStockIssue()
    {
        $stockItems = DailyStockItem::whereIn('daily_stock_id', [47])->with('dailyStock')->get();

        $stockItems->each(function (DailyStockItem $item) {

            $preAl = DailySaleItem::where('daily_sale_id', $item->dailyStock->pre_allocation_id)
                ->where('product_id', $item->product_id)->first();

            $availableQty = 0;
            if($preAl){
                $availableQty = getAvailableQty($preAl);
            }

            $item->setAttribute('available_qty', $availableQty);
            $item->setAttribute('required_qty', ($item->default_qty - $availableQty) < 0 ? 0 : ($item->default_qty - $availableQty));
            $item->save();
        });

    }

    function updateCustomerAccSalesTrans()
    {
        $customers = Customer::get();
        $customers->each(function (Customer $customer) {

            /** get customer account */
            $account = Account::where('accountable_id', $customer->id)->where('accountable_type', 'App\Customer')->first();

            if($account){
                $trans = TransactionRecord::where('account_id', 3)->whereHas('transaction', function ($q) use ($customer, $account) {
                    $q->where('customer_id', $customer->id);
                })->get();

                $trans->each(function (TransactionRecord $tran) use ($account) {

                    $record = new TransactionRecord();
                    $record->setAttribute('date', $tran->getAttribute('date'));
                    $record->setAttribute('amount', $tran->getAttribute('amount'));
                    $record->setAttribute('type', $tran->getAttribute('type'));
                    $record->setAttribute('account_id', $account->id);
                    $record->setAttribute('transaction_id', $tran->getAttribute('transaction_id'));
                    $record->save();
                });
            }
        });
        dd('Customer account related transactions updated successfully!!!');
    }

    function updateCustomerAccCashTrans()
    {
        $customers = Customer::get();
        $customers->each(function (Customer $customer) {

            /** get customer account */
            $account = Account::where('accountable_id', $customer->id)->where('accountable_type', 'App\Customer')->first();

            if($account){
                $trans = TransactionRecord::where('account_id', 1)->whereHas('transaction', function ($q) use ($customer, $account) {
                    $q->where('customer_id', $customer->id);
                })->get();

                $trans->each(function (TransactionRecord $tran) use ($account) {

                    $record = new TransactionRecord();
                    $record->setAttribute('date', $tran->getAttribute('date'));
                    $record->setAttribute('amount', $tran->getAttribute('amount'));
                    $record->setAttribute('type', $tran->getAttribute('type'));
                    $record->setAttribute('account_id', $account->id);
                    $record->setAttribute('transaction_id', $tran->getAttribute('transaction_id'));
                    $record->save();
                });
            }
        });
        dd('Customer account related transactions updated successfully!!!');
    }

    function updateCusTranCategory()
    {
        $customers = Customer::get();
        $customers->each(function (Customer $customer) {
            /** get customer account */
            $account = Account::where('accountable_id', $customer->id)->where('accountable_type', 'App\Customer')->first();

            if($account){
                $trans = TransactionRecord::where('account_id', $account->id)->get();
                $trans->each(function (TransactionRecord $tran) use ($account) {
                    $tran->setAttribute('category', 'Customer');
                    $tran->save();
                });
            }
        });
        dd('Customer accounts category updated successfully!!!');
    }

    public function createCollection(){
        $face = new FaceRecognitionRepository();
        $face->createCollection();
        dd("done");
    }

    function stockMinLevel()
    {
        $products = Product::get();
        $products->each(function (Product $product) {
            $stock = Stock::where('product_id', $product->id)->first();
            if($stock){
                $stock->setAttribute('min_stock_level', $product->min_stock_level);
                $stock->save();
            }
        });
        dd('Stock min level updated successfully!!!');
    }

    function updateTransRecords()
    {
        $trans = Transaction::get();
        $trans->each(function (Transaction $tran) {
            $records = $tran->records;
            $records->each(function (TransactionRecord $record) use ($tran) {
                $record->setAttribute('company_id', $tran->company_id);
                $record->setAttribute('customer_id', $tran->customer_id);
                $record->save();
            });
        });
        dd('Transaction records updated successfully!!!');
    }

    function UpdateSoldQty()
    {
        $order = SalesOrder::where('id', 1714)->first();
        dispatch(new UpdateSoldQty('Save', $order));
        dd('Sold QTY updated successfully!!!');
    }

    function removeOldData()
    {
        /*$trans = Transaction::where('company_id', 1)->get();
        $trans->each(function (Transaction $tran) {
            $tran->records()->delete();
            $tran->delete();
        });*/

        $orders = SalesOrder::where('company_id', 1)->whereBetween('id', [1, 1486])->get();
        $orders->each(function (SalesOrder $order) {
            $order->payments()->delete();
            $order->invoices()->delete();
            $order->delete();
        });
        dd('Sales order records deleted successfully!!!');
    }

    function createCusSupAccountForCompany()
    {
        $companies = Company::get();
        $companies->each(function (Company $company) {

            /** generate customer account */
            //$this->customer->internalCustomer($company);

            /** generate supplier account */
            $this->supplier->internalSupplier($company);

        });
        dd('Customer and supplier creation done successfully!!!');
    }

    function createSupplierForProductionUnit()
    {
        $units = ProductionUnit::get();
        $units->each(function (ProductionUnit $unit) {
            /** generate supplier account */
            $this->supplier->internalSupplierPu($unit);
        });
        dd('Supplier creation done successfully!!!');
    }

    function createSupplierForStore()
    {
        $stores = Store::get();
        $stores->each(function (Store $store) {
            /** generate supplier account */
            $this->supplier->internalSupplierStore($store);
        });
        dd('Supplier creation done successfully!!!');
    }

    function createCustomerForStore()
    {
        $stores = Store::get();
        $stores->each(function (Store $store) {
            /** generate customer account */
            $this->customer->internalCustomerStore($store);
        });
        dd('Customer creation done successfully!!!');
    }

    function updateChequesHand()
    {
        $cheques = ChequeInHand::get();
        $cheques->each(function (ChequeInHand $cheque) {

            $payment = $cheque->chequeable;
            $cheque->setAttribute('customer_id', $payment->customer_id);
            $cheque->setAttribute('daily_sale_id', $payment->daily_sale_id);
            $cheque->save();

        });
        dd('Cheques were updated successfully!!!');
    }

    function createCompanyCashAccount()
    {
        $companies = Company::get();
        $companies->each(function (Company $company) {
            $this->account->createCompanyCashAccount($company);
        });
        dd('Company cash accounts created successfully!!!');
    }

    function createCompanyChequeInHandAccount()
    {
        $companies = Company::get();
        $companies->each(function (Company $company) {
            $this->account->createCompanyChequeAccount($company);
        });
        dd('Company cheques in hand accounts created successfully!!!');
    }

    function transferCash()
    {
        $comAccount = Account::where('id', 72)->first();
        if($comAccount){
            /** get all cash transaction related the company */
            $trans = Transaction::where('company_id', 5)->get();
            $trans->each(function (Transaction $tran) use($comAccount){
                $records = $tran->records()->where('account_id', 1);
                $records->each(function (TransactionRecord $record) use($comAccount){
                    $record->setAttribute('account_id', $comAccount->id);
                    $record->save();
                });
            });
        }
        dd('Transferred cash related transactions successfully!!!');
    }

    function transferCheques()
    {
        $comAccount = Account::where('id', 76)->first();
        if($comAccount){
            /** get all cheque transaction related the company */
            $trans = Transaction::where('company_id', 5)->get();
            $trans->each(function (Transaction $tran) use($comAccount){
                $records = $tran->records()->where('account_id', 50);
                $records->each(function (TransactionRecord $record) use($comAccount){
                    $record->setAttribute('account_id', $comAccount->id);
                    $record->save();
                });
            });
        }
        dd('Transferred cheque related transactions successfully!!!');
    }

    function updateOrdersRoute()
    {
        $orders = SalesOrder::where('is_opining', 'Yes')->where('company_id', 5)
            ->where('is_credit_sales', 'Yes')->where('route_id', null)->get();

        if($orders){
            $orders->each(function (SalesOrder $order) {
                if($order->customer && !$order->route_id){
                    $order->setAttribute('route_id', $order->customer->route_id);
                    $order->save();
                }
            });
        }
        dd('Opening orders route updated successfully!!!');
    }

    function addExpenseRelatedTrans()
    {
        $expenseIds = SalesExpense::where('company_id', 5)->pluck('id');
        $expenses = Expense::whereIn('sales_expense_id', $expenseIds)->get();
        if($expenses){
            $expenses->each(function (Expense $expense) {

                /** get cash account */
                $creditAccount = Account::where('account_type_id', 1)->where('accountable_id', 5)
                    ->where('accountable_type', 'App\Company')->first();

                $debitAccount = Account::find(27);
                recordTransaction($expense, $debitAccount, $creditAccount, [
                    'date' => $expense->getAttribute('expense_date'),
                    'type' => 'Deposit',
                    'amount' => $expense->getAttribute('amount'),
                    'auto_narration' => 'Expense amount paid for '.$expense->getAttribute('notes'),
                    'manual_narration' => 'Expense amount paid for '.$expense->getAttribute('notes'),
                    'tx_type_id' => 1,
                    'company_id' => $expense->getAttribute('company_id'),
                ], 'Expense');

            });
        }
        dd('Expenses related transactions updated successfully!!!');
    }

    function yesterdayExpenseRelatedTrans()
    {
        $expenseIds = SalesExpense::where('company_id', 5)->where('daily_sale_id', 77)->pluck('id');
        $expenses = Expense::whereIn('sales_expense_id', $expenseIds)->get();
        if($expenses){
            $expenses->each(function (Expense $expense) {

                /** get cash account */
                $creditAccount = Account::where('account_type_id', 1)->where('accountable_id', 5)
                    ->where('accountable_type', 'App\Company')->first();

                $debitAccount = Account::find(27);
                recordTransaction($expense, $debitAccount, $creditAccount, [
                    'date' => $expense->getAttribute('expense_date'),
                    'type' => 'Deposit',
                    'amount' => $expense->getAttribute('amount'),
                    'auto_narration' => 'Expense amount paid for '.$expense->getAttribute('notes'),
                    'manual_narration' => 'Expense amount paid for '.$expense->getAttribute('notes'),
                    'tx_type_id' => 1,
                    'company_id' => $expense->getAttribute('company_id'),
                ], 'Expense');

            });
        }
        dd('Expenses related transactions updated successfully!!!');
    }

    function updateSalesLineStock()
    {
        $allocation = DailySale::find(78);
        $items = DailySaleItem::where('daily_sale_id', $allocation->id)->get();
        if($items){
            foreach ($items as $item) {
                $productId = $item->product_id;
                $storeId = $item->store_id;
                $quantity = $item->quantity;
                $stock = Stock::where('product_id', $productId)
                    ->where('store_id', $storeId)
                    ->where('company_id', $allocation->company_id)->first();
                if($stock){
                    $history = StockHistory::where('stock_id', $stock->id)
                        ->where('transable_id', $allocation->id)
                        ->where('transable_type', 'App\DailySale')->first();

                    if(!$history){
                        $oldQuantity = $stock->available_stock;
                        $requestQuantity = ($oldQuantity - $quantity);

                        $stock->available_stock = $requestQuantity;
                        $stock->save();

                        /** Create New Stock History */
                        $history = new StockHistory();
                        $history->stock_id = $stock->id;
                        $history->quantity = $quantity;
                        $history->transaction = 'Out';
                        $history->trans_date = carbon();
                        $history->trans_description = 'Stock taken for KS-TRINCO (2019-01-30)';
                        $history->type = 'Taken';
                        $history->save();
                        if ($allocation) {
                            $history->transable_id = $allocation->id;
                            $history->transable_type = 'App\\' . class_basename($allocation);
                            $history->save();
                        }
                    }
                }
            }
        }
        dd('Allocation stock updated successfully!!!');
    }

    function routeCustomersCompany()
    {
        $routes = Route::where('company_id', 6)->get();
        if($routes){
            $routes->each(function (Route $route) {
                $customers = Customer::where('route_id', $route->id)->get();
                $customers->each(function (Customer $customer) use($route) {
                    $customer->company_id = $route->company_id;
                    $customer->save();
                });
            });
        }
        dd('Customers company updated successfully!!!');
    }

    function updateTodayOrdersCompany()
    {
        $allocation = DailySale::find(84);
        $orders = SalesOrder::where('daily_sale_id', $allocation->id)->get();
        if($orders){
            $orders->each(function (SalesOrder $order) use ($allocation) {
                $order->company_id = $allocation->company_id;
                $order->save();
            });
        }
        dd('Orders('. count($orders) . ') company updated successfully!!!');
    }

    function updateTodayInvoicesCompany()
    {
        $allocation = DailySale::find(84);
        $invoices = Invoice::where('daily_sale_id', $allocation->id)->get();
        if($invoices){
            $invoices->each(function (Invoice $invoice) use ($allocation) {
                $invoice->company_id = $allocation->company_id;
                $invoice->save();
            });
        }
        dd('Invoices('. count($invoices) . ') company updated successfully!!!');
    }

    function updateTodayPaymentsCompany()
    {
        $allocation = DailySale::find(84);
        $payments = InvoicePayment::where('daily_sale_id', $allocation->id)->get();
        if($payments){
            $payments->each(function (InvoicePayment $payment) use ($allocation) {
                $payment->company_id = $allocation->company_id;
                $payment->save();
            });
        }
        dd('Payments('. count($payments) . ') company updated successfully!!!');
    }

    function updateTodayInvTransCompany()
    {
        $allocation = DailySale::find(84);
        $invoices = Invoice::where('daily_sale_id', $allocation->id)->pluck('id');
        $trans = Transaction::whereIn('transactionable_id', $invoices)
            ->where('transactionable_type', 'App\Invoice')->get();
        if($trans){
            $trans->each(function (Transaction $tran) use ($allocation) {
                $tran->company_id = $allocation->company_id;
                $tran->save();
            });
        }
        dd('Trans for invoice company updated successfully!!!');
    }

    function updateTodayPaymentTransCompany()
    {
        $allocation = DailySale::find(84);
        $payments = InvoicePayment::where('daily_sale_id', $allocation->id)->pluck('id');
        $trans = Transaction::whereIn('transactionable_id', $payments)
            ->where('transactionable_type', 'App\InvoicePayment')->get();
        if($trans){
            $trans->each(function (Transaction $tran) use ($allocation) {
                $tran->company_id = $allocation->company_id;
                $tran->save();
            });
        }
        dd('Trans for payment company updated successfully!!!');
    }

    function createRepCashAccount()
    {
        $reps = Rep::get();
        $reps->each(function (Rep $rep) {
            $this->account->createRepCashAccount($rep);
        });
        dd('Rep cash accounts created successfully!!!');
    }

    function createRepChequeInHandAccount()
    {
        $reps = Rep::get();
        $reps->each(function (Rep $rep) {
            $this->account->createRepChequeAccount($rep);
        });
        dd('Rep cheques in hand accounts created successfully!!!');
    }

    function addShortageRelatedTrans()
    {
        $handOvers = SalesHandover::where('status', 'Confirmed')->get();
        $handOvers->each(function (SalesHandover $handOver) {

            /** add a transaction for shortage amount */
            if($handOver->shortage > 0){

                /** get rep related cash account */
                $creditAccount = Account::where('account_type_id', 1)->where('accountable_id', $handOver->rep_id)
                    ->where('accountable_type', 'App\Rep')->first();

                $debitAccount = Account::find(104);
                recordTransaction($handOver, $debitAccount, $creditAccount, [
                    'date' => $handOver->getAttribute('date'),
                    'type' => 'Deposit',
                    'amount' => $handOver->getAttribute('shortage'),
                    'auto_narration' => 'The shortage amount of '.$handOver->getAttribute('shortage').' was identified during the sales',
                    'manual_narration' => 'The shortage amount of '.$handOver->getAttribute('shortage').' was identified during the sales',
                    'tx_type_id' => 37,
                    'company_id' => $handOver->getAttribute('company_id'),
                ], 'CashShortage');
                /** END */

            }
        });
        dd('Done');
    }

    function addExcessRelatedTrans()
    {
        $handOvers = SalesHandover::where('status', 'Confirmed')->get();
        $handOvers->each(function (SalesHandover $handOver) {

            /** add a transaction for excess amount */
            if($handOver->excess > 0){

                /** get rep related cash account */
                $creditAccount = Account::where('account_type_id', 1)->where('accountable_id', $handOver->rep_id)
                    ->where('accountable_type', 'App\Rep')->first();

                $debitAccount = Account::find(105);
                recordTransaction($handOver, $debitAccount, $creditAccount, [
                    'date' => $handOver->getAttribute('date'),
                    'type' => 'Deposit',
                    'amount' => $handOver->getAttribute('excess'),
                    'auto_narration' => 'The excess amount of '.$handOver->getAttribute('excess').' was identified during the sales',
                    'manual_narration' => 'The excess amount of '.$handOver->getAttribute('excess').' was identified during the sales',
                    'tx_type_id' => 39,
                    'company_id' => $handOver->getAttribute('company_id'),
                ], 'CashExcess');
                /** END */

            }

        });
    }

    function createShopCashAccount()
    {
        $shops = SalesLocation::where('type', 'Shop')->get();
        $shops->each(function (SalesLocation $salesLocation) {
            $this->account->createShopCashAccount($salesLocation);
        });
        dd('Shop cash accounts created successfully!!!');
    }

    function createShopChequeInHandAccount()
    {
        $shops = SalesLocation::where('type', 'Shop')->get();
        $shops->each(function (SalesLocation $salesLocation) {
            $this->account->createShopChequeAccount($salesLocation);
        });
        dd('Shop cheques in hand accounts created successfully!!!');
    }

    function addAdditionalFieldsToReturnItemsTable()
    {
        $returns = SalesReturn::get();
        $returns->each(function (SalesReturn $return) {
            $items = SalesReturnItem::where('sales_return_id', $return->id)->get();
            $items->each(function (SalesReturnItem $returnItem) use ($return){
                $returnItem->setAttribute('date', $return->getAttribute('date'));
                $returnItem->setAttribute('customer_id', $return->getAttribute('customer_id'));
                $returnItem->setAttribute('company_id', $return->getAttribute('company_id'));
                $returnItem->setAttribute('daily_sale_id', $return->getAttribute('daily_sale_id'));
                $returnItem->save();
            });
        });
        dd('Added successfully!!!');
    }

    function getTransActions()
    {
        $trans = Transaction::get()->groupBy('action');
        dd($trans);
    }

    function updateCashTransToTransfer()
    {
        $trans = Transaction::where('action', 'CashTransfer')->get();
        $trans->each(function (Transaction $transaction) {
            $transaction->setAttribute('action', 'Transfer');
            $transaction->save();
        });
        dd('Updated successfully!!!');
    }

    function removeCompanyBaseData()
    {
        /**  */
        $orders = SalesOrder::where('company_id', 6);
        $orderIds = SalesOrder::where('company_id', 6)->pluck('id')->toArray();

        $references = OpeningBalanceReference::whereIn('order_id', $orderIds);
        $references->delete();

        $orders->delete();

        $trans = Transaction::where('company_id', 6);
        $transIds = Transaction::where('company_id', 6)->pluck('id')->toArray();

        $records = TransactionRecord::whereIn('transaction_id', $transIds);
        $records->delete();

        $trans->delete();

        dd('Done!!');
    }

    function addRouteRepFieldsToReturnsTable()
    {
        $returns = SalesReturn::get();
        $returns->each(function (SalesReturn $return) {
            $return->setAttribute('route_id', $return->allocation->route_id);
            $return->setAttribute('rep_id', $return->allocation->rep_id);
            $return->save();
        });
        dd('Added successfully!!!');
    }

    function addRouteRepFieldsToReturnItemsTable()
    {
        $returnItems = SalesReturnItem::get();
        $returnItems->each(function (SalesReturnItem $returnItem) {
            $returnItem->setAttribute('route_id', $returnItem->salesReturn->route_id);
            $returnItem->setAttribute('rep_id', $returnItem->salesReturn->rep_id);
            $returnItem->save();
        });
        dd('Added successfully!!!');
    }

    function removeAluOpenInvoices()
    {
        $routes = Route::where('company_id', 6)->get();
        if($routes){
            $routes->each(function (Route $route) {
                $customerIds = Customer::where('route_id', $route->id)->pluck('id');
                $invoices = Invoice::whereIn('customer_id', $customerIds);
                $invoices->delete();
            });
        }
        dd('Invoice removed successfully!!!');
    }

    function removeAluSalesVisits()
    {
        $customerIds = Customer::where('company_id', 6)->pluck('id');
        $visits = DailySaleCustomer::whereIn('customer_id', $customerIds);
        $visits->delete();
        dd('Visits removed successfully!!!');
    }

    function isAllActualStockConfirmed()
    {
        $allocation = DailySale::find(144);
        dd($this->handOver->isAllActualStockConfirmed($allocation));
    }

    function clearAluStocks()
    {
        $stocks = Stock::where('company_id', 6)->where('store_id', 15)->get();
        $stocks->each(function (Stock $stock) {
            $stock->histories()->delete();
            $stock->available_stock = 0;
            $stock->save();
        });
        dd('Done');
    }

    function removeAluOpenOrders()
    {
        $routeIds = Route::where('id', 55)->pluck('id')->toArray();

        $orders = SalesOrder::whereIn('route_id', $routeIds)
            ->where('is_opining', 'Yes')->where('created_at' , '<', '2019-02-05 17:08:01');

        $orderIds = SalesOrder::whereIn('route_id', $routeIds)
            ->where('is_opining', 'Yes')->where('created_at' , '<', '2019-02-05 17:08:01')->pluck('id')->toArray();

        $invoices = Invoice::whereIn('sales_order_id', $orderIds);
        $invoiceIds = Invoice::whereIn('sales_order_id', $orderIds)->pluck('id')->toArray();

        $payments = InvoicePayment::whereIn('invoice_id', $invoiceIds)->get();
        $payments->each(function (InvoicePayment $payment) {
            $transaction = $payment->transaction;
            if($transaction){
                $transaction->records()->delete();
                $transaction->delete();
            }
            $payment->delete();
        });

        $invoices->delete();
        $orders->delete();

        dd('Alu opening data removed successfully!!!');
    }

    function removeAluDistributionOrders()
    {
        $routeIds = Route::where('id', 55)->pluck('id')->toArray();

        $orders = SalesOrder::whereIn('route_id', $routeIds)
            ->where('sales_type', 'Distribution')->where('company_id', 5);

        $orderIds = SalesOrder::whereIn('route_id', $routeIds)
            ->where('sales_type', 'Distribution')->where('company_id', 5)->pluck('id')->toArray();

        $invoices = Invoice::whereIn('sales_order_id', $orderIds);
        $invoiceIds = Invoice::whereIn('sales_order_id', $orderIds)->pluck('id')->toArray();

        $payments = InvoicePayment::whereIn('invoice_id', $invoiceIds)->get();
        $payments->each(function (InvoicePayment $payment) {
            $transaction = $payment->transaction;
            if($transaction){
                $transaction->records()->delete();
                $transaction->delete();
            }
            $payment->delete();
        });

        $invoices->delete();
        $orders->delete();

        dd('Alu opening data removed successfully!!!');
    }

    public function runNextDayAllocationManual()
    {
        /** remove available daily stock allocation */
        $allocation = DailySale::where('id', 194)->first();

        $data = [];
        $data['route'] = 57;
        $data['store'] = 15;
        $data['allocation'] = $allocation;
        $data['user'] = auth()->user();

        dispatch(new NextDayAllocationCreateJob($data));
    }

    function createStaffAccount()
    {
        $staffs = Staff::get();
        $staffs->each(function (Staff $staff) {
            $this->account->createStaffAccount($staff);
        });
        dd('Staff accounts created successfully!!!');
    }

    function deleteShortagesTrans()
    {
        /** remove all cash shortage transactions */
        $trans = Transaction::where('action', 'CashShortage')->get();
        if($trans){
            $trans->each(function (Transaction $tran) {
                $tran->records()->delete();
                $tran->delete();
            });
        }

        /** remove all shortage items from the list */
        SalesHandoverShortage::get()->each->delete();

        dd('Shortage Deleted');
    }

    function updateShortagesTrans()
    {
        /** update shortage transactions  */
        $handovers = SalesHandover::where('status', 'Confirmed')->get();
        $handovers->each(function (SalesHandover $handover) {
            if($handover->getAttribute('shortage') > 0){
                // save shortage
                $shortage = new SalesHandoverShortage();
                $shortage->setAttribute('daily_sale_id', $handover->getAttribute('daily_sale_id'));
                $shortage->setAttribute('sales_handover_id', $handover->getAttribute('id'));
                $shortage->setAttribute('rep_id', $handover->getAttribute('rep_id'));
                $shortage->setAttribute('date', $handover->getAttribute('date'));
                $shortage->setAttribute('amount', $handover->getAttribute('shortage'));
                $shortage->setAttribute('submitted_by', auth()->user()->id);
                $shortage->save();

                /** ADD TRANSACTION */
                /** get staff & user details */
                $rep = Rep::where('id', $shortage->getAttribute('rep_id'))->first();
                if($rep){
                    $staff = $rep->staff;
                    if($staff){
                        /** get rep related cash account */
                        $creditAccount = Account::where('account_type_id', 1)->where('accountable_id', $shortage->getAttribute('rep_id'))
                            ->where('accountable_type', 'App\Rep')->first();

                        /** get staff related account */
                        $debitAccount = Account::where('accountable_id', $staff->id)
                            ->where('accountable_type', 'App\Staff')->first();

                        recordTransaction($shortage, $debitAccount, $creditAccount, [
                            'date' => $shortage->getAttribute('date'),
                            'type' => 'Deposit',
                            'amount' => $shortage->getAttribute('amount'),
                            'auto_narration' => 'The shortage amount of '.$shortage->getAttribute('amount').' was identified during the sales',
                            'manual_narration' => 'The shortage amount of '.$shortage->getAttribute('amount').' was identified during the sales',
                            'tx_type_id' => 37,
                            'company_id' => $handover->getAttribute('company_id'),
                        ], 'CashShortage');
                        /** END */
                    }
                }
            }
        });
        dd('Update shortage related transaction...');
    }

    function deleteExcessTrans()
    {
        /** remove all cash excess transactions */
        $trans = Transaction::where('action', 'CashExcess')->get();
        if($trans){
            $trans->each(function (Transaction $tran) {
                $tran->records()->delete();
                $tran->delete();
            });
        }

        /** remove all excess items from the list */
        SalesHandoverExcess::get()->each->delete();

        dd('Excess Deleted');
    }

    function updateExcessTrans()
    {
        /** update excess transactions  */
        $handovers = SalesHandover::where('status', 'Confirmed')->get();
        $handovers->each(function (SalesHandover $handover) {
            if($handover->getAttribute('excess') > 0){
                // save excess
                $excess = new SalesHandoverExcess();
                $excess->setAttribute('daily_sale_id', $handover->getAttribute('daily_sale_id'));
                $excess->setAttribute('sales_handover_id', $handover->getAttribute('id'));
                $excess->setAttribute('rep_id', $handover->getAttribute('rep_id'));
                $excess->setAttribute('date', $handover->getAttribute('date'));
                $excess->setAttribute('amount', $handover->getAttribute('excess'));
                $excess->setAttribute('submitted_by', auth()->user()->id);
                $excess->save();

                /** get rep related cash account */
                $debitAccount = Account::where('account_type_id', 1)->where('accountable_id', $excess->getAttribute('rep_id'))
                    ->where('accountable_type', 'App\Rep')->first();

                /** get general excess related account */
                $creditAccount = Account::find(105);

                recordTransaction($excess, $debitAccount, $creditAccount, [
                    'date' => $excess->getAttribute('date'),
                    'type' => 'Deposit',
                    'amount' => $excess->getAttribute('amount'),
                    'auto_narration' => 'The excess amount of '.$excess->getAttribute('amount').' was identified during the sales',
                    'manual_narration' => 'The excess amount of '.$excess->getAttribute('amount').' was identified during the sales',
                    'tx_type_id' => 39,
                    'company_id' => $handover->getAttribute('company_id'),
                ], 'CashExcess');
                /** END */
            }
        });
        dd('Update excess related transaction...');
    }

    function createSpnForShopAccount()
    {
        $shops = SalesLocation::where('type', 'Shop')->get();
        $shops->each(function (SalesLocation $shop) {
            $this->account->createSpnAccount($shop);
        });
        dd('SPN accounts created for all shops successfully!!!');
    }

    function createSpnForStoreAccount()
    {
        $stores = Store::get();
        $stores->each(function (Store $store) {
            $this->account->createStoreSpnAccount($store);
        });
        dd('SPN accounts created for all stores successfully!!!');
    }

    function createSpnForPUnitAccount()
    {
        $units = ProductionUnit::get();
        $units->each(function (ProductionUnit $unit) {
            $this->account->createPUnitSpnAccount($unit);
        });
        dd('SPN accounts created for all units successfully!!!');
    }

    function createSpnForCompanyAccount()
    {
        $companies = Company::get();
        $companies->each(function (Company $company) {
            $this->account->createCompanySpnAccount($company);
        });
        dd('SPN accounts created for all companies successfully!!!');
    }

    function createCompanyAccount()
    {
        $companies = Company::get();
        $companies->each(function (Company $company) {
            $this->account->createCompanyAccount($company);
        });
        dd('Accounts created for all companies successfully!!!');
    }

    function createPUnitAccount()
    {
        $units = ProductionUnit::get();
        $units->each(function (ProductionUnit $unit) {
            $this->account->createPUnitAccount($unit);
        });
        dd('Accounts created for all units successfully!!!');
    }

    function createStoreAccount()
    {
        $stores = Store::get();
        $stores->each(function (Store $store) {
            $this->account->createStoreAccount($store);
        });
        dd('Accounts created for all stores successfully!!!');
    }

    function createShopAccount()
    {
        $shops = SalesLocation::where('type', 'Shop')->get();
        $shops->each(function (SalesLocation $shop) {
            $this->account->createShopAccount($shop);
        });
        dd('Accounts created for all shops successfully!!!');
    }

    /** purchase and purchase return */
    function createPurchaseAccount()
    {
        $this->account->createPurchaseAccount();
        dd('Purchase account created successfully!!!');
    }
    function createPurchaseReturnAccount()
    {
        $this->account->createPurchaseReturnAccount();
        dd('Purchase return account created successfully!!!');
    }
    /** END */

    /** sales and purchase accounts creation */
    function createPurchaseCompanyAccount()
    {
        $companies = Company::get();
        $companies->each(function (Company $company) {
            $this->account->createPurchaseCompanyAccount($company);
        });
        dd('Purchase accounts created for all companies successfully!!!');
    }

    function createSalesCompanyAccount()
    {
        $companies = Company::get();
        $companies->each(function (Company $company) {
            $this->account->createSalesCompanyAccount($company);
        });
        dd('Sales accounts created for all companies successfully!!!');
    }

    function createPurchasePUnitAccount()
    {
        $units = ProductionUnit::get();
        $units->each(function (ProductionUnit $unit) {
            $this->account->createPurchasePUnitAccount($unit);
        });
        dd('Purchase accounts created for all units successfully!!!');
    }

    function createSalesPUnitAccount()
    {
        $units = ProductionUnit::get();
        $units->each(function (ProductionUnit $unit) {
            $this->account->createSalesPUnitAccount($unit);
        });
        dd('Sales accounts created for all units successfully!!!');
    }

    function createPurchaseStoreAccount()
    {
        $stores = Store::get();
        $stores->each(function (Store $store) {
            $this->account->createPurchaseStoreAccount($store);
        });
        dd('Purchase accounts created for all stores successfully!!!');
    }

    function createSalesStoreAccount()
    {
        $stores = Store::get();
        $stores->each(function (Store $store) {
            $this->account->createSalesStoreAccount($store);
        });
        dd('Sales accounts created for all stores successfully!!!');
    }

    function createPurchaseShopAccount()
    {
        $shops = SalesLocation::where('type', 'Shop')->get();
        $shops->each(function (SalesLocation $shop) {
            $this->account->createPurchaseShopAccount($shop);
        });
        dd('Purchase accounts created for all shops successfully!!!');
    }

    function createSalesShopAccount()
    {
        $shops = SalesLocation::where('type', 'Shop')->get();
        $shops->each(function (SalesLocation $shop) {
            $this->account->createSalesShopAccount($shop);
        });
        dd('Sales accounts created for all shops successfully!!!');
    }

    function createDamageStoreForAllCompanies()
    {
        $companies = Company::get();
        $companies->each(function (Company $company) {
            $this->store->createDamageStore($company);
        });
        dd('Damaged stores created for all companies successfully!!!');
    }

    public function getDamagedStocks()
    {
        $damagedStores = Store::where('type', 'Damage')->get();
        $damagedStores->each(function (Store $damagedStore) {
            $damagedItems = DailySaleItem::where('damaged_qty', '>', 0)
                ->where(function ($query) use ($damagedStore) {
                    $query->whereHas('dailySale', function ($q) use ($damagedStore) {
                        $q->where('company_id', $damagedStore->company_id);
                    });
                })->get();
            if($damagedItems) {
                $damagedItems->each(function (DailySaleItem $dailySaleItem) use ($damagedStore) {

                    /** check stock available or not  */
                    $damagedStock = Stock::where('category', 'Damage')
                        ->where('company_id', $damagedStore->company_id)
                        ->where('product_id', $dailySaleItem->getAttribute('product_id'))
                        ->where('store_id', $damagedStore->id)->first();

                    if($damagedStock){
                        /** add qty to damage stock */
                        $damagedStock->available_stock = ($damagedStock->available_stock + $dailySaleItem->getAttribute('damaged_qty'));
                        $damagedStock->save();

                        /** add damaged history */
                        $dgStockHis = new StockHistory();
                        $dgStockHis->setAttribute('stock_id', $damagedStock->id);
                        $dgStockHis->setAttribute('quantity', $dailySaleItem->getAttribute('damaged_qty'));
                        $dgStockHis->setAttribute('rate', 0);
                        $dgStockHis->setAttribute('type', 'Damage');
                        $dgStockHis->setAttribute('transaction', 'In');
                        $dgStockHis->setAttribute('trans_date', carbon()->now()->toDateString());
                        $dgStockHis->setAttribute('trans_description', 'Damaged stocks from sales allocation');
                        $dgStockHis->setAttribute('store_id', $damagedStore->id);
                        $dgStockHis->save();
                    }else{
                        /** need to create a damaged stock and add stock history */
                        $newDgStock = new Stock();
                        $newDgStock->setAttribute('store_id', $damagedStore->id);
                        $newDgStock->setAttribute('available_stock', $dailySaleItem->getAttribute('damaged_qty'));
                        $newDgStock->setAttribute('product_id', $dailySaleItem->getAttribute('product_id'));
                        $newDgStock->setAttribute('notes', 'Damaged stocks from sales allocation');
                        $newDgStock->setAttribute('type', 'Auto');
                        $newDgStock->setAttribute('category', 'Damage');
                        $newDgStock->setAttribute('company_id', $damagedStore->getAttribute('company_id'));
                        $newDgStock->setAttribute('min_stock_level', '5000');
                        $newDgStock->save();

                        /** add damaged history */
                        $newDgStockHis = new StockHistory();
                        $newDgStockHis->setAttribute('stock_id', $newDgStock->getAttribute('id'));
                        $newDgStockHis->setAttribute('quantity', $dailySaleItem->getAttribute('damaged_qty'));
                        $newDgStockHis->setAttribute('rate', 0);
                        $newDgStockHis->setAttribute('type', 'Damage');
                        $newDgStockHis->setAttribute('transaction', 'In');
                        $newDgStockHis->setAttribute('trans_date', carbon()->now()->toDateString());
                        $newDgStockHis->setAttribute('trans_description', 'Damaged stocks from sales allocation');
                        $newDgStockHis->setAttribute('store_id', $damagedStore->id);
                        $newDgStockHis->save();
                    }
                });
            }
        });
        dd('Damaged stocks added successfully!!!');
    }

    public function getExcessStocks()
    {
        $allocations = DailySale::where('status', 'Completed')->get();
        $allocations->each(function (DailySale $allocation) {

            $handover = $allocation->salesHandover;
            $rep = Rep::where('id', $allocation->getAttribute('rep_id'))->first();
            $staff = $rep->staff;

            /** check whether excess available */
            $items = DailySaleItem::where('daily_sale_id', $allocation->getAttribute('id'))
                ->where('excess_qty', '>', 0)->get();

            $items = $items->transform(function($item) use ($allocation){
                $item->rate = getProductSellingPrice($allocation, $item->product_id, $item->excess_qty);
                $item->amount = ($item->excess_qty * getProductSellingPrice($allocation, $item->product_id, $item->excess_qty));
                return $item;
            });

            if($items->count()){

                /** calculate amount */
                $amount = $items->sum('amount');

                /** create stock excess record and items */
                $stockExcess = new StockExcess();
                $stockExcess->setAttribute('date', $handover->date);
                $stockExcess->setAttribute('amount', $amount);
                $stockExcess->setAttribute('notes', 'Stock excess from - '.$allocation->route->name);
                $stockExcess->setAttribute('prepared_by', auth()->id());
                $stockExcess->setAttribute('prepared_on', carbon()->now()->toDateTimeString());
                $stockExcess->setAttribute('route_id', $allocation->getAttribute('route_id'));
                $stockExcess->setAttribute('rep_id', $rep->id);
                $stockExcess->setAttribute('staff_id', $staff->id);
                $stockExcess->setAttribute('daily_sale_id', $allocation->getAttribute('id'));
                $stockExcess->setAttribute('sales_handover_id', $handover->id);
                $stockExcess->setAttribute('company_id', $allocation->getAttribute('company_id'));
                $stockExcess->save();

                foreach ($items as $saleItem) {
                    $stockExcessItem = new StockExcessItem();
                    $stockExcessItem->setAttribute('date', $stockExcess->getAttribute('date'));
                    $stockExcessItem->setAttribute('qty', $saleItem->excess_qty);
                    $stockExcessItem->setAttribute('rate', $saleItem->rate);
                    $stockExcessItem->setAttribute('amount', $saleItem->amount);
                    $stockExcessItem->setAttribute('product_id', $saleItem->product_id);
                    $stockExcessItem->setAttribute('store_id', $saleItem->store_id);
                    $stockExcessItem->setAttribute('stock_excess_id', $stockExcess->getAttribute('id'));
                    $stockExcessItem->save();
                }
            }
        });
        dd('Excess stocks recorded successfully!!!');
    }

    public function getShortageStocks()
    {
        $allocations = DailySale::where('status', 'Completed')->get();
        $allocations->each(function (DailySale $allocation) {

            $handover = $allocation->salesHandover;
            $rep = Rep::where('id', $allocation->getAttribute('rep_id'))->first();
            $staff = $rep->staff;

            /** check whether shortage available */
            $items = DailySaleItem::where('daily_sale_id', $allocation->getAttribute('id'))
                ->where('shortage_qty', '>', 0)->get();

            $items = $items->transform(function($item) use ($allocation){
                $item->rate = getProductSellingPrice($allocation, $item->product_id, $item->shortage_qty);
                $item->amount = ($item->shortage_qty * getProductSellingPrice($allocation, $item->product_id, $item->shortage_qty));
                return $item;
            });

            if($items->count()){

                /** calculate amount */
                $amount = $items->sum('amount');

                /** create stock shortage record and items */
                $stockShortage = new StockShortage();
                $stockShortage->setAttribute('date', $handover->date);
                $stockShortage->setAttribute('amount', $amount);
                $stockShortage->setAttribute('notes', 'Stock shortage from - '.$allocation->route->name);
                $stockShortage->setAttribute('prepared_by', auth()->id());
                $stockShortage->setAttribute('prepared_on', carbon()->now()->toDateTimeString());
                $stockShortage->setAttribute('route_id', $allocation->getAttribute('route_id'));
                $stockShortage->setAttribute('rep_id', $rep->id);
                $stockShortage->setAttribute('staff_id', $staff->id);
                $stockShortage->setAttribute('daily_sale_id', $allocation->getAttribute('id'));
                $stockShortage->setAttribute('sales_handover_id', $handover->id);
                $stockShortage->setAttribute('company_id', $allocation->getAttribute('company_id'));
                $stockShortage->save();

                foreach ($items as $saleItem) {
                    $stockShortageItem = new StockShortageItem();
                    $stockShortageItem->setAttribute('date', $stockShortage->getAttribute('date'));
                    $stockShortageItem->setAttribute('qty', $saleItem->shortage_qty);
                    $stockShortageItem->setAttribute('rate', $saleItem->rate);
                    $stockShortageItem->setAttribute('amount', $saleItem->amount);
                    $stockShortageItem->setAttribute('product_id', $saleItem->product_id);
                    $stockShortageItem->setAttribute('store_id', $saleItem->store_id);
                    $stockShortageItem->setAttribute('stock_shortage_id', $stockShortage->getAttribute('id'));
                    $stockShortageItem->save();
                }
            }
        });
        dd('Shortage stocks recorded successfully!!!');
    }

    function createCompanyCommissionAccount()
    {
        $companies = Company::get();
        $companies->each(function (Company $company) {
            $this->account->createCompanyCommissionAccount($company);
        });
        dd('Companies sales commission related account created successfully!!!');
    }

    function createRepCommissionAccount()
    {
        $reps = Rep::get();
        $reps->each(function (Rep $rep) {
            $this->account->createRepCommissionAccount($rep);
        });
        dd('Reps sales commission related account created successfully!!!');
    }

    function createVehicleAccount()
    {
        $vehicles = SalesLocation::where('type', 'Sales Van')->get();
        $vehicles->each(function (SalesLocation $vehicle) {
            $this->account->createVehicleAccount($vehicle);
        });
        dd('Reps sales commission related account created successfully!!!');
    }

    function updateAItoAIPLTrans()
    {
        $accountIds = ['2', '61', '62', '63', '64', '65', '66', '67'];
        $trans = Transaction::where('company_id', 1)->get();
        $trans->each(function (Transaction $tran) {
            $tran->setAttribute('company_id', 3);
            $tran->save();
        });
        dd('Trans updated successfully!!!');
    }

    function createCompanyVanGoodsShortageAccount()
    {
        $companies = Company::get();
        $companies->each(function (Company $company) {
            $this->account->createCompanyVanGoodsShortageAccount($company);
        });
        dd('Accounts created successfully!!!');
    }

    function createCompanyVanGoodsExcessAccount()
    {
        $companies = Company::get();
        $companies->each(function (Company $company) {
            $this->account->createCompanyVanGoodsExcessAccount($company);
        });
        dd('Accounts created successfully!!!');
    }

    function createProductionStoreForAllPUnits()
    {
        $units = ProductionUnit::get();
        $units->each(function (ProductionUnit $unit) {
            $this->store->createProductionStore($unit);
        });
        dd('Production stores created for all companies successfully!!!');
    }

    function createIssuedChequeAccount()
    {
        $companies = Company::get();
        $companies->each(function (Company $company) {
            $this->account->createIssuedChequeAccount($company);
        });
        dd('Accounts created successfully!!!');
    }

    function createDriverCommissionAccount()
    {
        $drivers = Staff::where('designation_id', 12)->get();
        $drivers->each(function (Staff $staff) {
            $this->account->createDriverCommissionAccount($staff);
        });
        dd('Driver sales commission related account created successfully!!!');
    }

    function createLabourCommissionAccount()
    {
        $labours = Staff::where('designation_id', 15)->get();
        $labours->each(function (Staff $staff) {
            $this->account->createLabourCommissionAccount($staff);
        });
        dd('Labour sales commission related account created successfully!!!');
    }

    function createReturnStoreForAllCompanies()
    {
        $units = ProductionUnit::get();
        $units->each(function (ProductionUnit $unit) {
            $this->store->createReturnStore($unit);
        });
        dd('Return stores created for all companies successfully!!!');
    }

    function updateBouncedDate()
    {
        $cheques = ChequeInHand::where('status', 'Bounced')->get();
        $cheques->each(function (ChequeInHand $cheque) {
            $cheque->bounced_date = carbon($cheque->updated_at)->toDateString();
            $cheque->save();
        });
        dd('Updated successfully!!!');
    }

    function updateChequeRepId()
    {
        $cheques = ChequeInHand::get();
        $cheques->each(function (ChequeInHand $cheque) {
            $cheque->rep_id = optional($cheque->dailySale)->rep_id;
            $cheque->save();
        });
        dd('Updated successfully!!!');
    }

    function getAllCustomerOutStandings()
    {
        $customers = Customer::where('company_id', 6)->get();
        $customers = $customers->map(function (Customer $customer){
            $customer->outstanding = cusOutstanding($customer)['balance'];
            return $customer;
        });
        dd($customers->pluck('outstanding', 'id'));
    }

}
