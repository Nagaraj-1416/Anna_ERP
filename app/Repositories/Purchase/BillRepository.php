<?php

namespace App\Repositories\Purchase;

use App\BillPayment;
use App\Http\Requests\Purchase\BillStoreRequest;
use App\Bill;
use App\Http\Requests\Purchase\CancelRequest;
use App\Repositories\BaseRepository;
use App\PurchaseOrder;
use Illuminate\Http\Request;

/**
 * Class BillRepository
 * @package App\Repositories\Purchase
 */
class BillRepository extends BaseRepository
{
    /**
     * BillRepository constructor.
     * @param Bill|null $bill
     */
    public function __construct(Bill $bill = null)
    {
        $this->setModel($bill ?? new Bill());
        $this->setCodePrefix('BL', 'bill_no');
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['bill_no', 'bill_date', 'due_date', 'amount', 'prepared_by', 'approval_status', 'approved_by', 'status',
            'purchase_order_id', 'supplier_id', 'business_type_id', 'notes', 'company_id'];

        $searchingColumns = ['bill_no', 'bill_date', 'due_date', 'amount', 'prepared_by', 'approval_status', 'approved_by', 'status',
            'purchase_order_id', 'supplier_id', 'business_type_id', 'notes', 'company_id'];

        $relation = [
            'supplier' => [
                ['as' => 'supplier_name', 'column' => 'display_name']
            ],
            'order' => [
                ['as' => 'purchase_order', 'column' => 'po_no']
            ]
        ];

        $data = $this->getTableData($request, $columns, $searchingColumns, $relation);
        $data['data'] = array_map(function ($item) {
            $item['bill_no'] = '<a href="' . route('purchase.bill.show', $item['id']) . '">' . $item['bill_no'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            $item['action'] .= actionBtn('Show', null, ['purchase.bill.show', [$item['id']]], ['class' => 'btn-success']);
            $item['action'] .= actionBtn('Edit', null, ['purchase.bill.edit', [$item['id']]]);
            $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-bill']);
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    public function saveBill($request, PurchaseOrder $order)
    {
        $this->model->setAttribute('bill_no', $this->getCode());
        $this->model->setAttribute('bill_date', carbon()->now()->toDateString());
        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('purchase_order_id', $order->getAttribute('id'));
        $this->model->setAttribute('supplier_id', $order->getAttribute('supplier_id'));
        $this->model->setAttribute('company_id', $order->getAttribute('company_id'));
        $this->model->setAttribute('store_id', $order->getAttribute('store_id'));
        $this->model->setAttribute('notes', $request->input('bill_notes'));

        $bill = $this->model->fill($request->toArray());
        $bill->save();
        return $bill;
    }

    /**
     * @param BillStoreRequest $request
     * @param Bill $bill
     * @return Bill
     */
    public function update(BillStoreRequest $request, Bill $bill)
    {
        $request->merge(['bill_no' => $bill->getAttribute('bill_no')]);
        $this->setModel($bill);
        $this->model->update($request->toArray());
        return $bill;
    }

    /**
     * @param Bill $bill
     * @return array
     * @throws \Exception
     */
    public function delete(Bill $bill): array
    {
        $bill->delete();
        return ['success' => true];
    }

    /**
     * @param string $method
     * @param Bill|null $bill
     * @return array
     */
    public function breadcrumbs(string $method, Bill $bill = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Bills'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Bills', 'route' => 'purchase.bill.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Bills', 'route' => 'purchase.bill.index'],
                ['text' => $bill->bill_no ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Bills', 'route' => 'purchase.bill.index'],
                ['text' => $bill->bill_no ?? ''],
                ['text' => 'Edit'],
            ],
            'print' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Bills', 'route' => 'purchase.bill.index'],
                ['text' => $bill->bill_no ?? ''],
                ['text' => 'Print Bill'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    public function getBills()
    {
        $filter = request()->input('filter');
        $search = request()->input('search');
        $userId = request()->input('userId');
        $overDue = request()->input('overdue');
        $ids = [];
        if ($overDue) {
            $forIds = Bill::whereNotIn('status', ['Paid', 'Canceled'])->whereDate('due_date', '<', carbon())->get();
            $ids = $forIds->filter(function ($collection) use ($overDue) {
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
        $supplierId = request()->input('supplierId');

        if ($overDue) {
            $bills = Bill::whereIn('id', $ids)->with('supplier', 'order')->orderBy('due_date', 'desc');
        } else {
            $bills = Bill::with('supplier', 'order')->orderBy('id', 'desc');
        }

        $lastWeek = carbon()->subWeek();
        if ($filter) {
            switch ($filter) {
                case 'draft':
                    $bills->where('status', 'Draft');
                    break;
                case 'open':
                    $bills->where('status', 'Open');
                    break;
                case 'overdue':
                    $bills->where('status', 'Overdue');
                    break;
                case 'partiallyPaid':
                    $bills->where('status', 'Partially Paid');
                    break;
                case 'paid':
                    $bills->where('status', 'Paid');
                    break;
                case 'canceled':
                    $bills->where('status', 'Canceled');
                    break;
                case 'recentlyCreated':
                    $bills->where('created_at', '>', $lastWeek);
                    break;
                case 'recentlyModified':
                    $bills->where('updated_at', '>', $lastWeek);
                    break;
            }
        }
        if ($search) {
            $bills->Where('bill_no', 'LIKE', '%' . $search . '%')
                ->orwhere(function ($query) use ($search) {
                    $query->whereHas('order', function ($q) use ($search) {
                        $q->where('po_no', 'LIKE', '%' . $search . '%');
                    })->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('display_name', 'LIKE', '%' . $search . '%');
                    });
                });
        }

        if ($userId) {
            $bills->where('prepared_by', $userId);
        }
        if ($supplierId) {
            $bills->where('supplier_id', $supplierId);
        }
        return $bills->paginate(12)->toArray();
    }

    /**
     * @param Bill $bill
     * @param CancelRequest $request
     */
    public function cancelBill(Bill $bill, CancelRequest $request)
    {
        $comment = $request->input('cancel_notes_bill');
        $bill->setAttribute('status', 'Canceled');
        $bill->save();
        createComment($request, $bill, $comment);
        if ($bill->payments) {
            /**
             * @var BillPayment $payment
             */
            foreach ($bill->payments as $payment) {
                $payment->setAttribute('status', 'Canceled');
                $payment->save();
                createComment($request, $payment, $comment);
            }
        }
    }
}