<?php

namespace App\Repositories\Finance;

use App\Account;
use App\ChequeInHand;
use App\Http\Requests\Finance\TransferRequest;
use App\Repositories\BaseRepository;
use App\Transfer;
use App\TransferItem;

/**
 * Class AccountRepository
 * @package App\Repositories\Finance
 */
class TransferRepository extends BaseRepository
{
    /**
     * TransferRepository constructor.
     * @param Transfer|null $transfer
     */
    public function __construct(Transfer $transfer = null)
    {
        $this->setModel($transfer ?? new Transfer());
    }

    public function index()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();

        if(isDirectorLevelStaff() || isAccountLevelStaff()){
            $transfers = Transfer::orderBy('id', 'desc')
                ->with('senderCompany', 'receiverCompany', 'transferBy');
        }else{
            $transfers = Transfer::where('transfer_by', auth()->id())->orderBy('id', 'desc')
                ->with('senderCompany', 'receiverCompany', 'transferBy');
        }

        if ($search) {
            $transfers->where(function ($query) use ($search) {
                $query->whereHas('senderCompany', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
            })->orwhere(function ($query) use ($search) {
                $query->whereHas('receiverCompany', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
            });
        }
        switch ($filter) {
            case 'recentlyCreated':
                $transfers->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $transfers->where('updated_at', '>', $lastWeek);
                break;
            case 'cashTransfer':
                $transfers->where('type', 'Cash');
                break;
            case 'chequeTransfer':
                $transfers->where('type', 'Cheque');
                break;
            case 'byHand':
                $transfers->where('transfer_mode', 'ByHand');
                break;
            case 'bankDeposit':
                $transfers->where('transfer_mode', 'DepositedToBank');
                break;
        }
        return $transfers->paginate(15)->toArray();
    }

    public function report()
    {

    }

    /**
     * @param TransferRequest $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(TransferRequest $request)
    {
        return $this->storeData($request);
    }

    /**
     * Delete transaction
     * @return array
     */
    public function delete()
    {
        try {
            $this->model->records()->delete();
            $this->model->delete();
            return ['success' => true, 'message' => 'Transaction deleted success!'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Transaction deleted failed!'];
        }
    }

    /**
     * @param TransferRequest $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function storeData(TransferRequest $request)
    {
        $request->merge(['sender' => $request->input('sender')]);
        $request->merge(['receiver' => $request->input('receiver')]);
        $request->merge(['transfer_by' => auth()->id()]);

        /** get credited to account */
        $senderCashAcc = Account::where('accountable_id', $request->input('sender'))
            ->where('accountable_type', 'App\Company')
            ->where('account_type_id', 1)
            ->first();

        $senderChequeAcc = Account::where('accountable_id', $request->input('sender'))
            ->where('accountable_type', 'App\Company')
            ->where('account_type_id', 19)
            ->first();

        /** get debited to account */
        $receiverCashAcc = Account::where('accountable_id', $request->input('receiver'))
            ->where('accountable_type', 'App\Company')
            ->where('account_type_id', 1)
            ->first();

        $receiverChequeAcc = Account::where('accountable_id', $request->input('receiver'))
            ->where('accountable_type', 'App\Company')
            ->where('account_type_id', 19)
            ->first();

        $this->model->setAttribute('handed_over_date', carbon()->now()->toDateString());
        $this->model->setAttribute('handed_over_time', carbon()->now()->toTimeString());
        $this->model->setAttribute('deposited_date', carbon()->now()->toDateString());
        $this->model->setAttribute('deposited_time', carbon()->now()->toTimeString());

        if($request->input('type') == 'Cash'){

            $request->merge(['credited_to' => $senderCashAcc->id]);
            $request->merge(['debited_to' => $receiverCashAcc->id]);

            $this->model->setAttribute('credited_to', $senderCashAcc->id);
            $this->model->setAttribute('debited_to', $receiverCashAcc->id);

            /** if transfer mode is bank deposit */
            if($request->input('transfer_mode') == 'DepositedToBank'){
                $this->model->setAttribute('status', 'Drafted');

                /** map deposited to account as debit account */
                $request->merge(['debited_to' => $request->input('deposited_to')]);
                $this->model->setAttribute('debited_to', $request->input('deposited_to'));

            }else{
                $this->model->setAttribute('status', 'Pending');
            }

            $transferCash = $this->model->fill($request->all());
            $transferCash->save();

            /** record transfer item */
            $transItemCash = new TransferItem();
            $transItemCash->setAttribute('date', $request->input('date'));
            $transItemCash->setAttribute('transfer_id', $transferCash->getAttribute('id'));
            $transItemCash->setAttribute('amount', $request->input('amount'));
            $transItemCash->setAttribute('status', 'Pending');
            $transItemCash->save();

        }else if($request->input('type') == 'Cheque'){

            $request->merge(['credited_to' => $senderChequeAcc->id]);
            $request->merge(['debited_to' => $receiverChequeAcc->id]);

            $this->model->setAttribute('credited_to', $senderChequeAcc->id);
            $this->model->setAttribute('debited_to', $receiverChequeAcc->id);

            $this->model->fill($request->all());
            $this->model->save();

            $records = $this->mapRecords($request, $this->model);

            $this->model->items()->createMany($records);

            /** update cheques in hand */
            $cheques = $request->input('cheques');
            foreach ($cheques as $key){
                ['cheque_no' => $chequeNo, 'bank_id' => $bankId] = chequeKeyToArray($key);
                $cihs = ChequeInHand::where('cheque_no', $chequeNo)->where('bank_id', $bankId)->get();
                foreach ($cihs as $cih){
                    $cih->is_transferred = 'Yes';
                    $cih->transferred_from = $senderChequeAcc->id;
                    $cih->transferred_to = $receiverChequeAcc->id;
                    $cih->save();
                }
            }
        }
        return $this->model;
    }

    protected function recordTransaction(Transfer $transfer)
    {
        $debitAccount = Account::find($transfer->getAttribute('debited_to'));
        $creditAccount = Account::find($transfer->getAttribute('credited_to'));
        recordTransaction($transfer, $debitAccount, $creditAccount, [
            'date' => $transfer->getAttribute('date'),
            'type' => 'Deposit',
            'amount' => $transfer->getAttribute('received_amount'),
            'auto_narration' => 'Cash amount of '.number_format($transfer->getAttribute('received_amount')).' was transferred to '.$debitAccount->name.' from '.$creditAccount->name,
            'manual_narration' => 'Cash amount of '.number_format($transfer->getAttribute('received_amount')).' was transferred to '.$debitAccount->name.' from '.$creditAccount->name,
            'tx_type_id' => 6,
            'company_id' => $transfer->getAttribute('receiver'),
        ], 'Transfer');
    }

    public function mapRecords(TransferRequest $request, Transfer $transfer = null): array
    {
        $records = [];

        $cheques = $request->input('cheques');

        foreach ($cheques as $key => $cheque) {

            /** get cheque details */
            $chequeDetails = getChequeDataByNo($cheque);

            array_push($records, [
                'date' => $request->input('date'),
                'transfer_id' => $transfer->getAttribute('id'),
                'amount' => $chequeDetails['eachTotal'],
                'cheque_no' => $cheque,
                'cheque_date' => $chequeDetails['date'],
                'cheque_type' => $chequeDetails['chequeType'],
                'bank_id' => $chequeDetails['bankId'],
                'status' => 'Pending',
            ]);
        }

        return $records;
    }

    public function approve(Transfer $transfer)
    {
        $transfer->setAttribute('received_by', auth()->id());
        $transfer->setAttribute('received_on', carbon()->now()->toDateTimeString());
        $transfer->setAttribute('status', 'Received');
        $transfer->save();

        /** record transaction */
        $this->recordTransaction($transfer);
    }

    public function decline(Transfer $transfer)
    {
        $transfer->setAttribute('received_by', auth()->id());
        $transfer->setAttribute('received_on', carbon()->now()->toDateTimeString());
        $transfer->setAttribute('status', 'Declined');
        $transfer->save();
    }

    public function statusUpdate(Transfer $transfer)
    {
        $request = request();
        $approval = $request->input('approval');

        if($approval == 'Approved'){

            /** update transfer items status */
            if($transfer->getAttribute('type') == 'Cash'){
                $cashItems = $transfer->items;
                if($cashItems){
                    foreach ($cashItems as $cashItem) {
                        $cashItem->amount = $request->input('received_amount');
                        $cashItem->status = 'Received';
                        $cashItem->save();
                    }
                }
            }else{
                $cheques = $request->input('cheques');
                if($cheques){
                    foreach ($cheques as $cheque) {
                        $chequeItem = TransferItem::where('id', $cheque)->first();
                        $chequeItem->status = 'Received';
                        $chequeItem->save();
                    }
                }

                /** update no select items as declined */
                $notCheckedItems = TransferItem::where('transfer_id', $transfer->getAttribute('id'))
                    ->whereNotIn('id', $cheques)->get();

                if($notCheckedItems){
                    foreach ($notCheckedItems as $notCheckedItem){
                        $notCheckedItem->status = 'Declined';
                        $notCheckedItem->save();
                    }
                }
            }

            $transfer->setAttribute('received_by', auth()->id());
            $transfer->setAttribute('received_on', carbon()->now()->toDateTimeString());
            $transfer->setAttribute('received_amount', $request->input('received_amount'));
            $transfer->setAttribute('status', 'Received');
            $transfer->save();

            /** record transaction */
            $this->recordTransaction($transfer);

        }else{

            $transferItems = $transfer->items;
            if($transferItems){
                foreach ($transferItems as $transferItem) {
                    $transferItem->status = 'Declined';
                    $transferItem->save();
                }
            }

            $transfer->setAttribute('received_by', auth()->id());
            $transfer->setAttribute('received_on', carbon()->now()->toDateTimeString());
            $transfer->setAttribute('status', 'Declined');
            $transfer->save();
        }
    }

    /**
     * @param string $method
     * @param Transfer|null $transfer
     * @return array
     */
    public function breadcrumbs(string $method, Transfer $transfer = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Transfers'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Transfers', 'route' => 'finance.transfer.index'],
                ['text' => 'Transfer Details'],
            ],
            'report' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Transfer Report'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}