<?php

namespace App\Repositories\Purchase;

use App\Account;
use App\BillPayment;
use App\Http\Requests\Purchase\BillCreditUpdateRequest;
use App\Http\Requests\Purchase\CancelRequest;
use App\Http\Requests\Purchase\PaymentStoreRequest;
use App\Bill;
use App\Http\Requests\Purchase\RefundRequest;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class PaymentRepository
 * @package App\Repositories\Purchase
 */
class PaymentRepository extends BaseRepository
{
    /**
     * PaymentRepository constructor.
     * @param BillPayment|null $payment
     */
    public function __construct(BillPayment $payment = null)
    {
        $this->setModel($payment ?? new BillPayment());
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['payment', 'payment_date', 'payment_type', 'payment_mode', 'payment_from', 'cheque_no',
            'cheque_date', 'account_no', 'deposited_date', 'bank_id', 'status', 'notes', 'prepared_by', 'bill_id'];

        $searchingColumns = ['payment', 'payment_date', 'payment_type', 'payment_mode', 'payment_from', 'cheque_no',
            'cheque_date', 'account_no', 'deposited_date', 'bank_id', 'status', 'notes', 'prepared_by', 'bill_id'];

        $relation = [
            'bill' => [
                ['as' => 'related_bill', 'column' => 'bill_no']
            ]
        ];

        $data = $this->getTableData($request, $columns, $searchingColumns, $relation);
        $data['data'] = array_map(function ($item) {
            $item['action'] = "<div class=\"button-group\">";
            $item['action'] .= actionBtn('Show', null, ['purchase.payment.show', [$item['id']]], ['class' => 'btn-success']);
            $item['action'] .= actionBtn('Edit', null, ['purchase.payment.edit', [$item['id']]]);
            $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-bill-payment']);
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    public function save(PaymentStoreRequest $request, Bill $bill)
    {
        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('bill_id', $bill->getAttribute('id'));
        $this->model->setAttribute('purchase_order_id', $bill->getAttribute('purchase_order_id'));
        $this->model->setAttribute('supplier_id', $bill->getAttribute('supplier_id'));
        $this->model->setAttribute('company_id', $bill->getAttribute('company_id'));
        $this->model->setAttribute('notes', $request->input('payment_notes'));

        $this->model->setAttribute('cheque_type', $request->input('cheque_type'));
        $this->model->setAttribute('cheque_no', $request->input('cheque_no'));
        $this->model->setAttribute('cheque_date', $request->input('cheque_date'));

        $this->model->setAttribute('account_no', $request->input('account_no'));
        $this->model->setAttribute('deposited_date', $request->input('deposited_date'));

        $this->model->setAttribute('card_holder_name', $request->input('card_holder_name'));
        $this->model->setAttribute('card_no', $request->input('card_no'));
        $this->model->setAttribute('expiry_date', $request->input('expiry_date'));

        $paymentMode = $request->input('payment_mode');
        if ($paymentMode == 'Cheque') {
            $this->model->setAttribute('bank_id', $request->input('cheque_bank_id'));
        } else {
            $this->model->setAttribute('bank_id', $request->input('dd_bank_id'));
        }

        $payment = $this->model->fill($request->toArray());
        $payment->save();

        /** update bill status as per the payment */
        if ($bill->getAttribute('amount') == $request->input('payment')) {
            $bill->setAttribute('status', 'Paid');
        } else {
            $bill->setAttribute('status', 'Partially Paid');
        }
        $bill->save();

        return $payment;
    }

    /**
     * @param PaymentStoreRequest $request
     * @param BillPayment $payment
     * @return mixed
     */
    public function update(PaymentStoreRequest $request, BillPayment $payment)
    {
        $this->setModel($payment);
        $paymentMode = $request->input('payment_mode');
        if ($paymentMode == 'Cash') {
            $this->model->setAttribute('cheque_type', null);
            $this->model->setAttribute('cheque_no', null);
            $this->model->setAttribute('cheque_date', null);
            $this->model->setAttribute('account_no', null);
            $this->model->setAttribute('deposited_date', null);
            $this->model->setAttribute('bank_id', null);
            $this->model->setAttribute('card_holder_name', null);
            $this->model->setAttribute('card_no', null);
            $this->model->setAttribute('expiry_date', null);
        } elseif ($paymentMode == 'Cheque') {
            $this->model->setAttribute('account_no', null);
            $this->model->setAttribute('deposited_date', null);
            $this->model->setAttribute('bank_id', $request->input('cheque_bank_id'));
            $this->model->setAttribute('card_holder_name', null);
            $this->model->setAttribute('card_no', null);
            $this->model->setAttribute('expiry_date', null);
        } elseif ($paymentMode == 'Direct Deposit') {
            $this->model->setAttribute('cheque_type', null);
            $this->model->setAttribute('cheque_no', null);
            $this->model->setAttribute('cheque_date', null);
            $this->model->setAttribute('bank_id', $request->input('dd_bank_id'));
            $this->model->setAttribute('card_holder_name', null);
            $this->model->setAttribute('card_no', null);
            $this->model->setAttribute('expiry_date', null);
        } elseif ($paymentMode == 'Credit Card') {
            $this->model->setAttribute('cheque_type', null);
            $this->model->setAttribute('cheque_no', null);
            $this->model->setAttribute('cheque_date', null);
            $this->model->setAttribute('account_no', null);
            $this->model->setAttribute('deposited_date', null);
        }
        $this->model->update($request->toArray());
        return $payment;
    }

    protected function recordTransaction(BillPayment $payment, $isEdit = false)
    {
        $creditAccount = Account::find($payment->paid_through);
        $debitAccount = Account::find(3);
        recordTransaction($payment, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $payment->payment,
            'auto_narration' => 'Payment '.number_format($payment->payment).' made to '.
                $payment->supplier->display_name.' and paid through from '.$payment->paidThrough->name,
            'manual_narration' => 'Payment '.number_format($payment->payment).' made to '.
                $payment->supplier->display_name.' and paid through from '.$payment->paidThrough->name,
            'tx_type_id' => 3,
            'supplier_id' => $payment->supplier_id,
            'company_id' => $payment->company_id,
        ], 'PaymentMade', $isEdit);
    }

    /**
     * @param BillPayment $payment
     * @return array
     * @throws \Exception
     */
    public function delete(BillPayment $payment): array
    {
        $payment->delete();
        return ['success' => true];
    }

    /**
     * @param string $method
     * @param BillPayment|null $payment
     * @return array
     */
    public function breadcrumbs(string $method, BillPayment $payment = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Payments'],
            ],
            'print' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Payments'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    /**
     * @param BillCreditUpdateRequest $request
     * @param BillPayment $payment
     * @return BillPayment
     */
    public function updateFromCredit(BillCreditUpdateRequest $request, BillPayment $payment)
    {
        $this->setModel($payment);
        $this->model->update($request->toArray());
        return $payment;
    }

    /**
     * @param BillPayment $payment
     * @param CancelRequest $request
     */
    public function cancelPayment(BillPayment $payment, CancelRequest $request)
    {
        $comment = $request->input('cancel_notes_payment');
        $payment->setAttribute('status', 'Canceled');
        $payment->save();

        /** update order as Cash / Credit */
        $bill = $payment->bill;
        if(billOutstanding($bill)['balance']){
            $bill->status = 'Open';
            $bill->save();
        }

        createComment($request, $payment, $comment);
    }

    /**
     * @param BillPayment $payment
     * @param RefundRequest $request
     */
    public function refundPayment(BillPayment $payment, RefundRequest $request)
    {
        $comment = $request->input('refund_notes_payment');
        $payment->setAttribute('status', 'Refunded');
        $payment->save();
        createComment($request, $payment, $comment);
    }
}