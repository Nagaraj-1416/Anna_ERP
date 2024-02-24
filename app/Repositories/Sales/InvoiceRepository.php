<?php

namespace App\Repositories\Sales;

use App\Account;
use App\Http\Requests\Sales\CancelRequest;
use App\Http\Requests\Sales\InvoiceStoreRequest;
use App\Http\Requests\Sales\RefundRequest;
use App\Invoice;
use App\Repositories\BaseRepository;
use App\SalesOrder;
use Illuminate\Http\Request;

/**
 * Class InvoiceRepository
 * @package App\Repositories\Sales
 */
class InvoiceRepository extends BaseRepository
{
    protected $document;

    /**
     * InvoiceRepository constructor.
     * @param Invoice|null $invoice
     */
    public function __construct(Invoice $invoice = null)
    {
        $this->setModel($invoice ?? new Invoice());
        $this->setCodePrefix('INV', 'invoice_no');
        $this->setRefPrefix('INV');
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['invoice_no', 'invoice_date', 'due_date', 'invoice_type', 'amount', 'prepared_by', 'approval_status',
            'approved_by', 'status', 'notes', 'sales_order_id', 'customer_id', 'business_type_id', 'company_id'];

        $searchingColumns = ['invoice_no', 'invoice_date', 'due_date', 'invoice_type', 'amount', 'prepared_by', 'approval_status',
            'approved_by', 'status', 'notes', 'sales_order_id', 'customer_id', 'business_type_id', 'company_id'];

        $relation = [
            'customer' => [
                ['as' => 'customer_name', 'column' => 'display_name']
            ],
            'order' => [
                ['as' => 'sales_order', 'column' => 'order_no']
            ]
        ];

        $data = $this->getTableData($request, $columns, $searchingColumns, $relation);
        $data['data'] = array_map(function ($item) {
            $item['invoice_no'] = '<a href="' . route('sales.invoice.show', $item['id']) . '">' . $item['invoice_no'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            $item['action'] .= actionBtn('Show', null, ['sales.invoice.show', [$item['id']]], ['class' => 'btn-success']);
            $item['action'] .= actionBtn('Edit', null, ['sales.invoice.edit', [$item['id']]]);
            $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-inv']);
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    /**
     * Api index method
     * @return mixed
     */
    public function apiIndex()
    {
        return Invoice::whereHas('order', function ($query) {
            $query->where('prepared_by', auth()->id());
        })
            ->orWhere('prepared_by', auth()->id())
            ->with('customer')
            ->get();
    }

    /**
     * @return mixed
     */
    public function todayIndexOld()
    {
        $customers = getAllAllocatedCustomers();
        $customerIds = $customers->pluck('id')->toArray();
        $status = ['Scheduled', 'Draft', 'Awaiting Approval', 'Open'];
        return Invoice::where(function ($query) use ($customerIds, $status) {
            $query->where(function ($query) use ($customerIds, $status) {
                $query->whereHas('order', function ($query) use ($customerIds, $status) {
                    $query->whereIn('status', $status)
                        ->orWhere('order_date', now()->toDateString());
                });
            })->orWhere(function ($query) {
                $query->whereHas('payments', function ($query) {
                    $query->where('payment_date', now()->toDateString())->where('prepared_by', auth()->id());
                });
            })->orWhere('invoice_date', now()->toDateString());
        })->whereHas('order', function ($query) use ($customerIds, $status) {
            $query->where('prepared_by', auth()->id());
        })->where('prepared_by', auth()->id())
            ->whereIn('customer_id', $customerIds)
            ->with(['company', 'customer', 'payments'])
            ->get();
    }

    /**
     * @return mixed
     */
    public function todayIndex()
    {
        $allocations = getRepAllocation();
        $allocation = $allocations->first();
        $customers = getAllAllocatedCustomers($allocations);
        $customerIds = $customers->pluck('id')->toArray();
        $status = ['Open','Partially Paid', 'Paid'];
        $orderIds = getAllocationCreditOrdersId();
        $invoices =  Invoice::whereIn('status', $status)
            ->whereIn('customer_id', $customerIds)->where(function ($q) use ($orderIds, $allocation) {
                $q->where(function ($q) use ($orderIds, $allocation) {
                    $q->whereBetween('invoice_date', [$allocation->from_date, $allocation->to_date])->where('prepared_by', auth()->id());
                })->orWhereHas('order', function ($orders) use ($orderIds) {
                    $orders->whereIn('id', $orderIds);
                })->orWhereHas('order', function ($orders) use ($orderIds) {
                    $orders->where('is_opining', 'Yes')->whereIn('id', $orderIds);
                });
            })->with(['company', 'customer', 'payments', 'order.openingReference'])->get();

        return $invoices->transform(function ($item){
            if ($item->order->is_opining == 'Yes'){
                $item->invoice_no =  $item->order->openingReference->invoice_no ?? $item->invoice_no;
            }
            $item->order = null;
            return $item;
        });
    }

    /**
     * @param Request $request
     * @param SalesOrder $order
     * @param bool $isApi
     * @return Invoice
     */
    public function save(Request $request, SalesOrder $order, $isApi = false)
    {
        // Check Duplication
        $uuid = $request->input('uuid');
        if ($isApi && $uuid){
            $duplicateItem = Invoice::where('uuid', $uuid)->first();
            if ($duplicateItem){
                return $duplicateItem->load(['customer']);
            }
            $this->model->setAttribute('uuid', $uuid);
        }


        if (!$isApi) {
            $request->merge(['ref' => $this->generateRef()]);
        }
        $request->merge(['invoice_no' => $this->getCode()]);

        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('ref', $order->getAttribute('ref'));
        $this->model->setAttribute('sales_order_id', $order->getAttribute('id'));
        $this->model->setAttribute('customer_id', $order->getAttribute('customer_id'));
        $this->model->setAttribute('route_id', $order->getAttribute('route_id'));
        $this->model->setAttribute('business_type_id', $order->getAttribute('business_type_id'));
        $this->model->setAttribute('company_id', $order->getAttribute('company_id'));
        $this->model->setAttribute('notes', $request->input('invoice_notes'));
        $this->model->setAttribute('sales_location_id', $order->sales_location_id);
        if ($isApi) {
            $dailySale = getRepAllocation()->first();
            $this->model->setAttribute('daily_sale_id', $dailySale ? $dailySale->id : null);
            if (!$this->model->id && $request->input('created_at')){
                $createdAt = carbon($request->input('created_at'));
                $this->model->setAttribute('invoice_date', $createdAt->toDateString());
                $this->model->setAttribute('created_at', $createdAt->toDateTimeString());
            }
        }
        $invoice = $this->model->fill($request->toArray());
        $invoice->save();

        /** update order invoice status as per the invoice amount */
        /** @var SalesOrder $order */
        $order = $order->refresh();
        if ($order->getAttribute('total') == $order->invoices->sum('amount')) {
            $order->setAttribute('invoice_status', 'Invoiced');
        } else {
            $order->setAttribute('invoice_status', 'Partially Invoiced');
        }
        $order->save();
        $this->recordTransaction($invoice);
        return $invoice->refresh();
    }

    protected function recordTransaction(Invoice $invoice, $isEdit = false)
    {
        $debitAccount = Account::find(3); // Account Receivable
        $creditAccount = Account::find(48); // Sales
        recordTransaction($invoice, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $invoice->amount,
            'auto_narration' => 'Invoice #'.$invoice->ref.', '.number_format($invoice->amount).' created against to '.
                $invoice->customer->display_name,
            'manual_narration' => 'Invoice #'.$invoice->ref.', '.number_format($invoice->amount).' created against to '.
                $invoice->customer->display_name,
            'tx_type_id' => 27,
            'customer_id' => $invoice->customer_id,
            'company_id' => $invoice->company_id,
        ], 'InvoiceCreation', $isEdit);
    }

    /**
     * @param Request $request
     * @param Invoice $invoice
     * @return Invoice
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->merge(['invoice_no' => $invoice->getAttribute('invoice_no')]);
        $request->merge(['ref' => $invoice->getAttribute('ref')]);
        /** @var SalesOrder $order */
        $order = $invoice->order;
        if (!$invoice->sales_location_id) {
            $request->merge(['sales_location_id' => $order ? $order->sales_location_id : null]);
        }
        $this->setModel($invoice);
        $this->model->update($request->toArray());
        $order = $order->refresh();
        if ($order->getAttribute('total') == $order->invoices->sum('amount')) {
            $order->setAttribute('invoice_status', 'Invoiced');
        } else {
            $order->setAttribute('invoice_status', 'Partially Invoiced');
        }
        $order->save();
        $this->recordTransaction($invoice, true);
        return $invoice;
    }

    /**
     * @param Invoice $invoice
     * @return array
     */
    public function delete(Invoice $invoice): array
    {
        try {
            $invoice->delete();
            return ['success' => true, 'message' => 'Deleted success'];
        } catch (\Exception $e) {
            return ['success' => true, 'message' => 'Deleted failed'];
        }

    }

    /**
     * @param string $method
     * @param Invoice|null $invoice
     * @return array
     */
    public function breadcrumbs(string $method, Invoice $invoice = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Invoices'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Invoices', 'route' => 'sales.invoice.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Invoices', 'route' => 'sales.invoice.index'],
                ['text' => $invoice->invoice_no ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Invoices', 'route' => 'sales.invoice.index'],
                ['text' => $invoice->invoice_no ?? ''],
                ['text' => 'Edit'],
            ],
            'print' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Invoices', 'route' => 'sales.invoice.index'],
                ['text' => $invoice->invoice_no ?? ''],
                ['text' => 'Print Invoice'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    /**
     * @return mixed
     */
    public function getInvoices()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $userId = \request()->input('userId');
        $overDue = \request()->input('overdue');
        $customerId = \request()->input('customerId');
        $lastWeek = carbon()->subWeek();
        $start = \request()->input('from_date');
        $end = \request()->input('to_date');
        $dateRange = \request()->input('dateRange') ?? false;
        $ids = [];
        if ($overDue) {
            $forIDs = Invoice::whereIn('company_id', userCompanyIds(loggedUser()))
                ->where('customer_id', '!=', null)
                ->whereNotIn('status', ['Paid', 'Canceled', 'Refunded'])
                ->whereDate('due_date', '<', carbon())->get();
            $ids = $forIDs->filter(function ($collection) use ($overDue) {
                $dueDate = $collection->due_date;
                $diff = carbon()->diffInDays(carbon($dueDate));
                if ($overDue == '>90' || $overDue == '&gt;90') {
                    if ($diff > 90) return $collection;
                    return null;
                }
                if ((int)$overDue == 60) {
                    if ($diff <= (int)$overDue && $diff > 30) return $collection;
                    return null;
                }
                if ((int)$overDue == 90) {
                    if ($diff > 60 && $diff <= (int)$overDue) return $collection;
                    return null;
                }
                if ($diff <= (int)$overDue) return $collection;
                return null;
            })->pluck('id')->toArray();
        }
        if ($overDue) {
            $invoices = Invoice::where('customer_id', '!=', null)->whereIn('id', $ids)->with('customer', 'order')->orderBy('due_date', 'desc');
        } else {
            $invoices = Invoice::where('customer_id', '!=', null)->with('customer', 'order')->orderBy('id', 'desc');
        }
        if ($search) {
            $invoices->where(function ($q) use ($search) {
                $q->where('invoice_no', 'LIKE', '%' . $search . '%')
                    ->orWhere('due_date', 'LIKE', '%' . $search . '%')
                    ->orWhere(function ($query) use ($search) {
                        $query->whereHas('customer', function ($q) use ($search) {
                            $q->where('display_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('mobile', 'LIKE', '%' . $search . '%');
                        });
                    })->orWhere(function ($query) use ($search) {
                        $query->whereHas('order', function ($q) use ($search) {
                            $q->where('order_no', 'LIKE', '%' . $search . '%')
                                ->orWhere('status', 'LIKE', '%' . $search . '%');
                        });
                    });
            });
        }
        switch ($filter) {
            case 'drafted':
                $invoices->where('status', 'Draft');
                break;
            case 'Open':
                $invoices->where('status', 'Open');
                break;
            case 'Overdue':
                $invoices->where('status', 'Overdue');
                break;
            case 'PartiallyPaid':
                $invoices->where('status', 'Partially Paid');
                break;
            case 'Paid':
                $invoices->where('status', 'Paid');
                break;
            case 'Canceled':
                $invoices->where('status', 'Canceled');
                break;
            case 'recentlyCreated':
                $invoices->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $invoices->where('updated_at', '>', $lastWeek);
                break;
            case 'today':
                $start = carbon()->toDateString();
                $end = carbon()->toDateString();
                $dateRange = true;
                break;
        }
        if ($userId) {
            $invoices->where('prepared_by', $userId);
        }

        if ($customerId) {
            $invoices->where('customer_id', $customerId);
        }
        if ($dateRange) {
            $invoices->whereBetween('invoice_date', [$start, $end]);
        }
        return $invoices->paginate(16)->toArray();
    }

    /**
     * @param Invoice $invoice
     * @param Request $request
     * @return Invoice
     */
    public function cancelInvoice(Invoice $invoice, Request $request)
    {
        $comment = $request->input('cancel_notes_invoice');
        $invoice->setAttribute('status', 'Canceled');
        $invoice->save();
        createComment($request, $invoice, $comment);
        if ($invoice->payments) {
            foreach ($invoice->payments as $payment) {
                $payment->setAttribute('status', 'Canceled');
                $payment->save();
                createComment($request, $payment, $comment);

                /**
                 * if payment mode cheque and cheque in hand related
                 * Update cheque in hand status to Cancel
                 * Remove cheque hand related to transaction
                 */
                /*if($payment->chequeInHand && $payment->payment_mode == 'Cheque'){
                    $chequeInHand = $payment->chequeInHand;
                    $chequeInHand->status = 'Canceled';
                    $chequeInHand->save();
                }*/
            }
        }

        /**
         * BEGIN
         * update transaction record when entire invoice cancel
         *  Transaction Type - Invoice Cancel
         *      DR - Sales
         *      CR - Account Receivable
         */
        $debitAccount = Account::find(48); // Sales
        $creditAccount = Account::find(3); // Account Receivable
        recordTransaction($invoice, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $invoice->amount,
            'auto_narration' => 'Invoice amount of '.number_format($invoice->payment).' is canceled ('.
                $invoice->ref.')',
            'manual_narration' => 'Invoice amount of '.number_format($invoice->payment).' is canceled ('.
                $invoice->ref.')',
            'tx_type_id' => 15,
            'customer_id' => $invoice->customer_id,
            'company_id' => $invoice->company_id,
        ], 'InvoiceCancel', false);

        return $invoice;
    }

    /**
     * @param Invoice $invoice
     * @param Request $request
     * @return Invoice
     */
    public function refundInvoice(Invoice $invoice, Request $request)
    {
        $comment = $request->input('refund_notes_invoice');
        $invoice->setAttribute('status', 'Refunded');
        $invoice->save();
        createComment($request, $invoice, $comment);
        if ($invoice->payments) {
            foreach ($invoice->payments as $payment) {
                $payment->setAttribute('status', 'Refunded');
                $payment->save();
                createComment($request, $payment, $comment);
            }
        }
        return $invoice;
    }
}
