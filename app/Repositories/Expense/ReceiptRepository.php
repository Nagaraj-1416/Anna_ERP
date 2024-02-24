<?php

namespace App\Repositories\Expense;

use App\Account;
use App\ChequeInHand;
use App\Expense;
use App\ExpenseCheque;
use App\ExpenseItem;
use App\ExpensePayment;
use App\IssuedCheque;
use App\Repositories\BaseRepository;
use App\Repositories\General\DocumentRepository;
use Illuminate\Http\Request;

/**
 * Class ReceiptRepository
 * @package App\Repositories\Expense
 */
class ReceiptRepository extends BaseRepository
{
    /** @var ExpenseRepository */
    protected $expenseRepository;

    /** @var DocumentRepository  */
    protected $document;

    /**
     * ReceiptRepository constructor.
     * @param Expense|null $expense
     * @param ExpenseRepository $expenseRepository
     * @param DocumentRepository $document
     */
    public function __construct(Expense $expense = null, ExpenseRepository $expenseRepository, DocumentRepository $document)
    {
        $this->expenseRepository = $expenseRepository;
        $this->document = $document;
        $this->setModel($expense ?? new Expense());
        $this->setCodePrefix('EX', 'expense_no');
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $expenses = Expense::whereIn('company_id', userCompanyIds(loggedUser()))
            ->orderBy('id', 'desc')->with('type', 'company', 'preparedBy');
        if ($search) {
            $expenses->where(function($q) use($search){
                $q->where('expense_no', 'LIKE', '%' . $search . '%')
                    ->orWhere('status', 'LIKE', '%' . $search . '%')
                    ->orWhere('expense_date', 'LIKE', '%' . $search . '%')
                    ->orwhere(function ($query) use ($search) {
                        $query->whereHas('customer', function ($q) use ($search) {
                            $q->where('display_name', 'LIKE', '%' . $search . '%');
                        });
                    });
            });
        }
        switch ($filter) {
            case 'Office':
                $expenses->where('expense_category', 'Office');
                break;
            case 'Van':
                $expenses->where('expense_category', 'Van');
                break;
            case 'Shop':
                $expenses->where('expense_category', 'Shop');
                break;
            case 'recentlyCreated':
                $expenses->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $expenses->where('updated_at', '>', $lastWeek);
                break;
        }

        return $expenses->paginate(12)->toArray();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(Request $request)
    {
        return $this->storeData($request);
    }

    /**
     * @param Expense $expense
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(Expense $expense, Request $request)
    {
        $this->setModel($expense);

        /** remove expense related transaction */
        //$transaction = $expense->transaction;
        //$transaction->records()->delete();
        //$transaction->delete();
        /** end */

        //return $this->storeData($request);

        $expense->setAttribute('expense_date', $request->input('expense_date'));
        $expense->setAttribute('expense_time', $request->input('expense_time'));
        $expense->setAttribute('amount', $request->input('amount'));
        $expense->setAttribute('supplier_id', $request->input('supplier_id'));
        $expense->setAttribute('notes', $request->input('notes'));

        // Salary, Salary Advance, Bonus, Commission
        // EPF, ETF, NBT, Vat, Rent
        $monthFieldTypes = ['12', '13', '11', '15', '20', '21', '22', '23', '33'];
        if(in_array($request->input('type_id'), $monthFieldTypes)){
            $expense->setAttribute('month', $request->input('month'));
        }

        // Salary, Salary Advance, Bonus, Loan -->
        // Commission, Allowance, Fine, Transport -->
        $staffFieldType = ['12', '13', '11', '14', '15', '3', '9', '8'];
        if(in_array($request->input('type_id'), $staffFieldType)){
            $expense->setAttribute('staff_id', $request->input('staff_id'));
        }

        // Loan -->
        $installPeriodFieldType = ['14'];
        if(in_array($request->input('type_id'), $installPeriodFieldType)){
            $expense->setAttribute('installment_period', $request->input('installment_period'));
        }

        // Allowance -->
        $daysFieldType = ['3'];
        if(in_array($request->input('type_id'), $daysFieldType)){
            $expense->setAttribute('no_of_days', $request->input('no_of_days'));
        }

        // Vehicle Repair, Fuel, Service, Parking, Vehicle Maintenance, Lease, Fine, transport -->
        $vehicleFieldType = ['6', '2', '5', '17', '18', '9', '8', '16'];
        if(in_array($request->input('type_id'), $vehicleFieldType)){
            $expense->setAttribute('vehicle_id', $request->input('vehicle_id'));
        }

        // Fuel -->
        $literFieldType = ['2'];
        if(in_array($request->input('type_id'), $literFieldType)){
            $expense->setAttribute('liter', $request->input('liter'));
        }

        // Fuel -->
        $odometerFieldType = ['2'];
        if(in_array($request->input('type_id'), $odometerFieldType)){
            $expense->setAttribute('odometer', $request->input('odometer'));
        }

        // Vehicle Repair -->
        $whatRepairedFieldType = ['6'];
        if(in_array($request->input('type_id'), $whatRepairedFieldType)){
            $expense->setAttribute('what_was_repaired', $request->input('what_was_repaired'));
        }

        // Vehicle Repair, Service, Machine Maintenance -->
        $changedItemFieldType = ['6', '16', '29'];
        if(in_array($request->input('type_id'), $changedItemFieldType)){
            $expense->setAttribute('changed_item', $request->input('changed_item'));
        }

        // Vehicle Repair, Machine Maintenance -->
        $supplierFieldType = ['6', '29'];
        if(in_array($request->input('type_id'), $supplierFieldType)){
            $expense->setAttribute('supplier_id', $request->input('supplier_id'));
        }

        // Vehicle & Machine Repair, Machine Maintenance -->
        $expiryDateFieldType = ['6', '29'];
        if(in_array($request->input('type_id'), $expiryDateFieldType)){
            $expense->setAttribute('repair_expiry_date', $request->input('repair_expiry_date'));
        }

        // Vehicle & Machine Repair, Machine Maintenance -->
        $repairingShopFieldType = ['6', '29'];
        if(in_array($request->input('type_id'), $repairingShopFieldType)){
            $expense->setAttribute('repairing_shop', $request->input('repairing_shop'));
        }

        // Vehicle, Machine Repair, Service, Machine Maintenance -->
        $labourChargeFieldType = ['6', '16', '29'];
        if(in_array($request->input('type_id'), $labourChargeFieldType)){
            $expense->setAttribute('labour_charge', $request->input('labour_charge'));
        }

        // Vehicle Repair, Service -->
        $driverFieldType = ['6', '16'];
        if(in_array($request->input('type_id'), $driverFieldType)){
            $expense->setAttribute('driver_id', $request->input('driver_id'));
        }

        // Vehicle Repair -->
        $odoAtRepairFieldType = ['6'];
        if(in_array($request->input('type_id'), $odoAtRepairFieldType)){
            $expense->setAttribute('odo_at_repair', $request->input('odo_at_repair'));
        }

        // Service -->
        $serviceStationFieldType = ['16'];
        if(in_array($request->input('type_id'), $serviceStationFieldType)){
            $expense->setAttribute('service_station', $request->input('service_station'));
        }

        // Service -->
        $odoAtServiceFieldType = ['16'];
        if(in_array($request->input('type_id'), $odoAtServiceFieldType)){
            $expense->setAttribute('odo_at_service', $request->input('odo_at_service'));
        }

        // Parking -->
        $parkingNameFieldType = ['5'];
        if(in_array($request->input('type_id'), $parkingNameFieldType)){
            $expense->setAttribute('parking_name', $request->input('parking_name'));
        }

        // Vehicle Maintenance -->
        $vehicleMainTypeFieldType = ['17'];
        if(in_array($request->input('type_id'), $vehicleMainTypeFieldType)){
            $expense->setAttribute('vehicle_maintenance_type', $request->input('vehicle_maintenance_type'));
        }

        // Vehicle Maintenance, Income Tax, CEB, Telephone, Room Charge, Water -->
        $fromDateFieldType = ['17', '24', '25', '27', '7', '26'];
        if(in_array($request->input('type_id'), $fromDateFieldType)){
            $expense->setAttribute('from_date', $request->input('from_date'));
        }

        // Vehicle Maintenance, Income Tax, CEB, Telephone, Room Charge, Water -->
        $toDateFieldType = ['17', '24', '25', '27', '7', '26'];
        if(in_array($request->input('type_id'), $toDateFieldType)){
            $expense->setAttribute('to_date', $request->input('to_date'));
        }

        // Lease -->
        $noMonthsFieldType = ['18'];
        if(in_array($request->input('type_id'), $noMonthsFieldType)){
            $expense->setAttribute('no_of_months', $request->input('no_of_months'));
        }

        // Fine -->
        $fineFieldType = ['9'];
        if(in_array($request->input('type_id'), $fineFieldType)){
            $expense->setAttribute('fine_reason', $request->input('fine_reason'));
        }

        // Transport -->
        $fromDestinationFieldType = ['8'];
        if(in_array($request->input('type_id'), $fromDestinationFieldType)){
            $expense->setAttribute('from_destination', $request->input('from_destination'));
        }

        // Transport -->
        $toDestinationFieldType = ['8'];
        if(in_array($request->input('type_id'), $toDestinationFieldType)){
            $expense->setAttribute('to_destination', $request->input('to_destination'));
        }

        // Transport -->
        $noOfBagsFieldType = ['8'];
        if(in_array($request->input('type_id'), $noOfBagsFieldType)){
            $expense->setAttribute('no_of_bags', $request->input('no_of_bags'));
        }

        // CEB, Telephone, Water -->
        $accountNumberFieldType = ['25', '27', '26'];
        if(in_array($request->input('type_id'), $accountNumberFieldType)){
            $expense->setAttribute('account_number', $request->input('account_number'));
        }

        // CEB, Water -->
        $unitsReadingFieldType = ['25', '26'];
        if(in_array($request->input('type_id'), $unitsReadingFieldType)){
            $expense->setAttribute('units_reading', $request->input('units_reading'));
        }

        // Machine Maintenance -->
        $machineFieldType = ['29'];
        if(in_array($request->input('type_id'), $machineFieldType)){
            $expense->setAttribute('machine', $request->input('machine'));
        }

        // Festival Expense -->
        $festivalNameFieldType = ['31'];
        if(in_array($request->input('type_id'), $festivalNameFieldType)){
            $expense->setAttribute('festival_name', $request->input('festival_name'));
        }

        // Donation -->
        $donatedToFieldType = ['32'];
        if(in_array($request->input('type_id'), $donatedToFieldType)){
            $expense->setAttribute('donated_to', $request->input('donated_to'));
        }

        // Donation -->
        $donatedReasonFieldType = ['32'];
        if(in_array($request->input('type_id'), $donatedReasonFieldType)){
            $expense->setAttribute('donated_reason', $request->input('donated_reason'));
        }

        // Room Charge -->
        $hotelNameFieldType = ['7'];
        if(in_array($request->input('type_id'), $hotelNameFieldType)){
            $expense->setAttribute('hotel_name', $request->input('hotel_name'));
        }

        // OD Interest, CHQ Book Issue -->
        $bankNumberFieldType = ['34', '35'];
        if(in_array($request->input('type_id'), $bankNumberFieldType)){
            $expense->setAttribute('bank_number', $request->input('bank_number'));
        }
        $expense->save();

        return $expense;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function storeData(Request $request)
    {
        if (!$this->model->getAttribute('expense_no')) {
            $this->model->setAttribute('expense_no', $this->getCode());
            $this->model->setAttribute('prepared_by', auth()->id());
        }

        $expenseMode = $request->input('expense_mode');
        $expenseCategory = $request->input('expense_category');
        $approvalRequired = $request->input('approval_required');

        $this->model->setAttribute('expense_mode', $expenseMode);
        $this->model->setAttribute('expense_category', 'Office');
        $this->model->setAttribute('approval_required', $approvalRequired);

        if($expenseMode == 'ForOthers' && $expenseCategory == 'Office'){
            $this->model->setAttribute('branch_id', $request->input('branch_id'));
            $this->model->setAttribute('expense_category', $expenseCategory);
        }

        if($expenseMode == 'ForOthers' && $expenseCategory == 'Shop'){
            $this->model->setAttribute('shop_id', $request->input('shop_id'));
            $this->model->setAttribute('expense_category', $expenseCategory);
        }

        $this->model->setAttribute('type_id', $request->input('type_id'));
        $this->model->setAttribute('expense_date', $request->input('expense_date'));
        $this->model->setAttribute('expense_time', $request->input('expense_time'));

        if($request->input('cheque_type') == 'Own'){
            $issuedChequeAccount = Account::where('prefix', 'IssuedCheque')
                ->where('accountable_type', 'App\Company')
                ->where('accountable_id', $request->input('company_id'))
                ->first();
            $this->model->setAttribute('paid_through', $issuedChequeAccount->id); // Issued Cheques
        }else{
            $this->model->setAttribute('paid_through', $request->input('paid_through'));
        }

        $this->model->setAttribute('supplier_id', $request->input('supplier_id'));

        $this->model->setAttribute('expense_account', $request->input('expense_account'));

        $this->model->setAttribute('company_id', $request->input('company_id'));
        $this->model->setAttribute('amount', $request->input('amount'));

        $this->model->setAttribute('payment_mode', 'Cash');

        $this->model->setAttribute('cheque_no', null);
        $this->model->setAttribute('cheque_date', null);

        $this->model->setAttribute('account_no', null);
        $this->model->setAttribute('deposited_date', null);

        $this->model->setAttribute('card_holder_name', null);
        $this->model->setAttribute('card_no', null);
        $this->model->setAttribute('expiry_date', null);

        if ($request->input('payment_mode') == 'Cheque'){
            $this->model->setAttribute('cheque_no', $request->input('cheque_no'));
            $this->model->setAttribute('cheque_date', $request->input('cheque_date'));
            if ($request->input('cheque_bank_id')){
                $request->merge(['bank_id' => $request->input('cheque_bank_id')]);
            }
        }
        if ($request->input('payment_mode') == 'Direct Deposit'){
            $this->model->setAttribute('account_no', $request->input('account_no'));
            $this->model->setAttribute('deposited_date', $request->input('deposited_date'));
            if ($request->input('dd_bank_id')){
                $request->merge(['bank_id' => $request->input('dd_bank_id')]);
            }
        }
        if ($request->input('payment_mode') == 'Credit Card'){
            $this->model->setAttribute('card_holder_name', $request->input('card_holder_name'));
            $this->model->setAttribute('card_no', $request->input('card_no'));
            $this->model->setAttribute('expiry_date', $request->input('expiry_date'));
            if ($request->input('cc_bank_id')){
                $request->merge(['bank_id' => $request->input('cc_bank_id')]);
            }
        }
        $this->model->setAttribute('bank_id', $request->input('bank_id'));
        $this->model->setAttribute('notes', $request->input('notes'));
        $this->model->setAttribute('cheque_type', $request->input('cheque_type'));

        // Salary, Salary Advance, Bonus, Commission
        // EPF, ETF, NBT, Vat, Rent
        $monthFieldTypes = ['12', '13', '11', '15', '20', '21', '22', '23', '33'];
        if(in_array($request->input('type_id'), $monthFieldTypes)){
            $this->model->setAttribute('month', $request->input('month'));
        }

        // Salary, Salary Advance, Bonus, Loan -->
        // Commission, Allowance, Fine, Transport -->
        $staffFieldType = ['12', '13', '11', '14', '15', '3', '9', '8'];
        if(in_array($request->input('type_id'), $staffFieldType)){
            $this->model->setAttribute('staff_id', $request->input('staff_id'));
        }

        // Loan -->
        $installPeriodFieldType = ['14'];
        if(in_array($request->input('type_id'), $installPeriodFieldType)){
            $this->model->setAttribute('installment_period', $request->input('installment_period'));
        }

        // Allowance -->
        $daysFieldType = ['3'];
        if(in_array($request->input('type_id'), $daysFieldType)){
            $this->model->setAttribute('no_of_days', $request->input('no_of_days'));
        }

        // Vehicle Repair, Fuel, Service, Parking, Vehicle Maintenance, Lease, Fine, transport -->
        $vehicleFieldType = ['6', '2', '5', '17', '18', '9', '8', '16'];
        if(in_array($request->input('type_id'), $vehicleFieldType)){
            $this->model->setAttribute('vehicle_id', $request->input('vehicle_id'));
        }

        // Fuel -->
        $literFieldType = ['2'];
        if(in_array($request->input('type_id'), $literFieldType)){
            $this->model->setAttribute('liter', $request->input('liter'));
        }

        // Fuel -->
        $odometerFieldType = ['2'];
        if(in_array($request->input('type_id'), $odometerFieldType)){
            $this->model->setAttribute('odometer', $request->input('odometer'));
        }

        // Vehicle Repair -->
        $whatRepairedFieldType = ['6'];
        if(in_array($request->input('type_id'), $whatRepairedFieldType)){
            $this->model->setAttribute('what_was_repaired', $request->input('what_was_repaired'));
        }

        // Vehicle Repair, Service, Machine Maintenance -->
        $changedItemFieldType = ['6', '16', '29'];
        if(in_array($request->input('type_id'), $changedItemFieldType)){
            $this->model->setAttribute('changed_item', $request->input('changed_item'));
        }

        // Vehicle Repair, Machine Maintenance -->
        $supplierFieldType = ['6', '29'];
        if(in_array($request->input('type_id'), $supplierFieldType)){
            $this->model->setAttribute('supplier_id', $request->input('supplier_id'));
        }

        // Vehicle & Machine Repair, Machine Maintenance -->
        $expiryDateFieldType = ['6', '29'];
        if(in_array($request->input('type_id'), $expiryDateFieldType)){
            $this->model->setAttribute('repair_expiry_date', $request->input('repair_expiry_date'));
        }

        // Vehicle & Machine Repair, Machine Maintenance -->
        $repairingShopFieldType = ['6', '29'];
        if(in_array($request->input('type_id'), $repairingShopFieldType)){
            $this->model->setAttribute('repairing_shop', $request->input('repairing_shop'));
        }

        // Vehicle, Machine Repair, Service, Machine Maintenance -->
        $labourChargeFieldType = ['6', '16', '29'];
        if(in_array($request->input('type_id'), $labourChargeFieldType)){
            $this->model->setAttribute('labour_charge', $request->input('labour_charge'));
        }

        // Vehicle Repair, Service -->
        $driverFieldType = ['6', '16'];
        if(in_array($request->input('type_id'), $driverFieldType)){
            $this->model->setAttribute('driver_id', $request->input('driver_id'));
        }

        // Vehicle Repair -->
        $odoAtRepairFieldType = ['6'];
        if(in_array($request->input('type_id'), $odoAtRepairFieldType)){
            $this->model->setAttribute('odo_at_repair', $request->input('odo_at_repair'));
        }

        // Service -->
        $serviceStationFieldType = ['16'];
        if(in_array($request->input('type_id'), $serviceStationFieldType)){
            $this->model->setAttribute('service_station', $request->input('service_station'));
        }

        // Service -->
        $odoAtServiceFieldType = ['16'];
        if(in_array($request->input('type_id'), $odoAtServiceFieldType)){
            $this->model->setAttribute('odo_at_service', $request->input('odo_at_service'));
        }

        // Parking -->
        $parkingNameFieldType = ['5'];
        if(in_array($request->input('type_id'), $parkingNameFieldType)){
            $this->model->setAttribute('parking_name', $request->input('parking_name'));
        }

        // Vehicle Maintenance -->
        $vehicleMainTypeFieldType = ['17'];
        if(in_array($request->input('type_id'), $vehicleMainTypeFieldType)){
            $this->model->setAttribute('vehicle_maintenance_type', $request->input('vehicle_maintenance_type'));
        }

        // Vehicle Maintenance, Income Tax, CEB, Telephone, Room Charge, Water -->
        $fromDateFieldType = ['17', '24', '25', '27', '7', '26'];
        if(in_array($request->input('type_id'), $fromDateFieldType)){
            $this->model->setAttribute('from_date', $request->input('from_date'));
        }

        // Vehicle Maintenance, Income Tax, CEB, Telephone, Room Charge, Water -->
        $toDateFieldType = ['17', '24', '25', '27', '7', '26'];
        if(in_array($request->input('type_id'), $toDateFieldType)){
            $this->model->setAttribute('to_date', $request->input('to_date'));
        }

        // Lease -->
        $noMonthsFieldType = ['18'];
        if(in_array($request->input('type_id'), $noMonthsFieldType)){
            $this->model->setAttribute('no_of_months', $request->input('no_of_months'));
        }

        // Fine -->
        $fineFieldType = ['9'];
        if(in_array($request->input('type_id'), $fineFieldType)){
            $this->model->setAttribute('fine_reason', $request->input('fine_reason'));
        }

        // Transport -->
        $fromDestinationFieldType = ['8'];
        if(in_array($request->input('type_id'), $fromDestinationFieldType)){
            $this->model->setAttribute('from_destination', $request->input('from_destination'));
        }

        // Transport -->
        $toDestinationFieldType = ['8'];
        if(in_array($request->input('type_id'), $toDestinationFieldType)){
            $this->model->setAttribute('to_destination', $request->input('to_destination'));
        }

        // Transport -->
        $noOfBagsFieldType = ['8'];
        if(in_array($request->input('type_id'), $noOfBagsFieldType)){
            $this->model->setAttribute('no_of_bags', $request->input('no_of_bags'));
        }

        // CEB, Telephone, Water -->
        $accountNumberFieldType = ['25', '27', '26'];
        if(in_array($request->input('type_id'), $accountNumberFieldType)){
            $this->model->setAttribute('account_number', $request->input('account_number'));
        }

        // CEB, Water -->
        $unitsReadingFieldType = ['25', '26'];
        if(in_array($request->input('type_id'), $unitsReadingFieldType)){
            $this->model->setAttribute('units_reading', $request->input('units_reading'));
        }

        // Machine Maintenance -->
        $machineFieldType = ['29'];
        if(in_array($request->input('type_id'), $machineFieldType)){
            $this->model->setAttribute('machine', $request->input('machine'));
        }

        // Festival Expense -->
        $festivalNameFieldType = ['31'];
        if(in_array($request->input('type_id'), $festivalNameFieldType)){
            $this->model->setAttribute('festival_name', $request->input('festival_name'));
        }

        // Donation -->
        $donatedToFieldType = ['32'];
        if(in_array($request->input('type_id'), $donatedToFieldType)){
            $this->model->setAttribute('donated_to', $request->input('donated_to'));
        }

        // Donation -->
        $donatedReasonFieldType = ['32'];
        if(in_array($request->input('type_id'), $donatedReasonFieldType)){
            $this->model->setAttribute('donated_reason', $request->input('donated_reason'));
        }

        // Room Charge -->
        $hotelNameFieldType = ['7'];
        if(in_array($request->input('type_id'), $hotelNameFieldType)){
            $this->model->setAttribute('hotel_name', $request->input('hotel_name'));
        }

        // OD Interest, CHQ Book Issue -->
        $bankNumberFieldType = ['34', '35'];
        if(in_array($request->input('type_id'), $bankNumberFieldType)){
            $this->model->setAttribute('bank_number', $request->input('bank_number'));
        }

        $this->model->save();

        /** save to expense cheques table if Third Party */
        if($request->input('payment_mode') == 'Cheque'
            && $request->input('cheque_type') == 'Third Party'){

            $thirdPartyCheques = $request->input('third_party_cheques');
            $thirdPartyCheques = explode(',', $thirdPartyCheques);
            foreach ($thirdPartyCheques as $thirdPartyCheque){
                $chequeHands = ChequeInHand::where('cheque_no', $thirdPartyCheque)->get();
                foreach ($chequeHands as $chequeHand){
                    $expCheque = new ExpenseCheque();
                    $expCheque->setAttribute('expense_id', $this->model->getAttribute('id'));
                    $expCheque->setAttribute('cheque_in_hand_id', $chequeHand->id);
                    $expCheque->save();
                }
            }
        }

        /** save to issued cheques table if Own*/
        if($request->input('payment_mode') == 'Cheque'
            && $request->input('cheque_type') == 'Own'){

            $issuedCheque = new IssuedCheque();
            $issuedCheque->setAttribute('registered_date', carbon()->now()->toDateString());
            $issuedCheque->setAttribute('amount', $request->input('amount'));
            $issuedCheque->setAttribute('cheque_date', $request->input('cheque_date'));
            $issuedCheque->setAttribute('cheque_no', $request->input('cheque_no'));
            $issuedCheque->setAttribute('bank_id', $request->input('bank_id'));
            $issuedCheque->setAttribute('chequeable_id', $this->model->getAttribute('id'));
            $issuedCheque->setAttribute('chequeable_type', 'App\Expense');
            $issuedCheque->setAttribute('supplier_id', $request->input('supplier_id'));
            $issuedCheque->setAttribute('customer_id', $request->input('customer_id'));
            $issuedCheque->setAttribute('prepared_by', auth()->id());
            $issuedCheque->setAttribute('company_id', $request->input('company_id'));
            $issuedCheque->save();
        }

        /*if($approvalRequired == 'No'){
            $this->recordTransaction($this->model);
        }*/

        $files = $request->file('files');
        if ($files) {
            foreach ($files as $file) {
                $this->document->setDocumentable($this->model);
                $this->document->save($file);
            }
        }
        return $this->model;
    }

    /**
     * @param Expense $expense
     * @return Expense
     */
    public function edit(Expense $expense)
    {
        $expense->setAttribute('expense_type_name', $expense->type ? $expense->type->name : null);
        $expense->setAttribute('expense_type', $expense->type ? $expense->type->id : null);
        $expense->setAttribute('paid_through_name', $expense->paidThrough ? $expense->paidThrough->name : null);
        $expense->setAttribute('paid_through', $expense->paidThrough ? $expense->paidThrough->id : null);
        $expense->setAttribute('expense_account_name', $expense->expenseAccount ? $expense->expenseAccount->name : null);
        $expense->setAttribute('expense_account', $expense->expenseAccount ? $expense->expenseAccount->id : null);
        $expense->setAttribute('company_name', $expense->company ? $expense->company->name : null);
        return $expense;
    }

    public function delete()
    {
        try {
            /** remove expense related transaction */
            $transaction = $this->model->transaction;
            $transaction->records()->delete();
            $transaction->delete();
            /** end */
            $this->model->delete();
            return ['success' => true, 'message' => 'Expense payment deleted success!'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Expense payment deleted failed!'];
        }
    }

    public function approve(Expense $expense)
    {
        $cashPayments = $expense->payments->where('payment_mode', 'Cash');
        return ['success' => true, 'message' => 'Expense approved successfully!'];
    }

    /*protected function recordTransaction(Expense $expense, $isEdit = false)
    {
        $debitAccount = Account::find($expense->expense_account);
        $creditAccount = Account::find($expense->paid_through);
        recordTransaction($expense, $debitAccount, $creditAccount, [
            'date' => $expense->expense_date,
            'type' => 'Deposit',
            'amount' => $expense->amount,
            'auto_narration' => 'Expense amount paid for '.$expense->type->name,
            'manual_narration' => $expense->notes,
            'tx_type_id' => 1,
            'company_id' => $expense->company_id,
            'supplier_id' => $expense->supplier_id,
            'customer_id' => $expense->customer_id,
        ], 'Expense', $isEdit);
    }*/

    /**
     * @param Expense|null $expense
     * @param string|null $method
     * @return array
     */
    public function breadcrumbs(Expense $expense = null, string $method = null): array
    {
        if (!$method) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $method = $backtrace[1]['function'] ?? null;
        }
        $base = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Expense', 'route' => 'expense.index'],
        ];
        $breadcrumbs = [
            'index' => array_merge($base, [
                ['text' => 'Payments'],
            ]),
            'create' => array_merge($base, [
                ['text' => 'Payments', 'route' => 'expense.receipt.index'],
                ['text' => 'Create']
            ]),
            'show' => array_merge($base, [
                ['text' => 'Payments', 'route' => 'expense.receipt.index'],
                ['text' => $expense->expense_no ?? ''],
            ]),
            'edit' => array_merge($base, [
                ['text' => 'Payments', 'route' => 'expense.receipt.index'],
                ['text' => $expense->expense_no ?? ''],
                ['text' => 'Edit'],
            ]),
            'add-items' => array_merge($base, [
                ['text' => 'Payments', 'route' => 'expense.receipt.index'],
                ['text' => $expense->expense_no ?? ''],
                ['text' => 'Add Items'],
            ]),
            'add-payment' => array_merge($base, [
                ['text' => 'Payments', 'route' => 'expense.receipt.index'],
                ['text' => $expense->expense_no ?? ''],
                ['text' => 'Add Payment'],
            ])
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    public function storeCashPayment(Expense $expense, $request)
    {
        $payment = new ExpensePayment();
        $payment->setAttribute('payment', $request->input('payment'));
        $payment->setAttribute('payment_date', $expense->getAttribute('expense_date'));
        $payment->setAttribute('payment_mode', 'Cash');
        $payment->setAttribute('notes', $request->input('notes'));
        $payment->setAttribute('prepared_by', auth()->id());
        $payment->setAttribute('expense_id', $expense->getAttribute('id'));
        $payment->setAttribute('company_id', $expense->getAttribute('company_id'));
        $payment->save();

        if($expense->getAttribute('approval_required') === 'No'){
            $this->recordCashPaymentTransaction($expense, $payment);
        }

        return $payment;
    }

    protected function recordCashPaymentTransaction(Expense $expense, ExpensePayment $expensePayment, $isEdit = false)
    {
        if($expense->getAttribute('expense_mode') === 'ForOthers')
        {
            /** Expense Account */
            $debitAccount1 = Account::where('id', $expense->getAttribute('expense_account'))
                ->first();

            $creditAccount1 = Account::where('account_type_id', 3)
                ->where('prefix', 'Company')
                ->where('accountable_id', $expense->getAttribute('company_id'))
                ->where('accountable_type', 'App\Company')
                ->first();

            recordTransaction($expense, $debitAccount1, $creditAccount1, [
                'date' => now()->toDateString(),
                'type' => 'Deposit',
                'amount' => $expensePayment->getAttribute('payment'),
                'auto_narration' => 'Expense amount paid for '.$expense->type->name.'- By Cash',
                'manual_narration' => 'Expense amount paid for '.$expense->type->name.'- By Cash',
                'tx_type_id' => 1,
                'supplier_id' => $expense->getAttribute('supplier_id'),
                'company_id' => $expense->getAttribute('company_id')
            ], 'Expense', $isEdit);

            /** second transaction */
            $debitAccount2 = Account::where('account_type_id', 3)
                ->where('prefix', 'Company')
                ->where('accountable_id', $expense->getAttribute('branch_id'))
                ->where('accountable_type', 'App\Company')
                ->first();

            $creditAccount2 = Account::where('accountable_id', $expense->getAttribute('company_id'))
                ->where('accountable_type', 'App\Company')
                ->where('prefix', 'Cash')
                ->where('account_type_id', 1)
                ->first();

            recordTransaction($expense, $debitAccount2, $creditAccount2, [
                'date' => now()->toDateString(),
                'type' => 'Deposit',
                'amount' => $expensePayment->getAttribute('payment'),
                'auto_narration' => 'Expense amount paid for '.$expense->type->name.'- By Cash',
                'manual_narration' => 'Expense amount paid for '.$expense->type->name.'- By Cash',
                'tx_type_id' => 1,
                'supplier_id' => $expense->getAttribute('supplier_id'),
                'company_id' => $expense->getAttribute('company_id')
            ], 'Expense', $isEdit);

        } else {
            $debitAccount = Account::find($expense->expense_account);
            $creditAccount = Account::where('accountable_id', $expense->getAttribute('company_id'))
                ->where('accountable_type', 'App\Company')
                ->where('prefix', 'Cash')
                ->where('account_type_id', 1)
                ->first();

            recordTransaction($expense, $debitAccount, $creditAccount, [
                'date' => $expense->expense_date,
                'type' => 'Deposit',
                'amount' => $expense->amount,
                'auto_narration' => 'Expense amount paid for '.$expense->type->name.'- By Cash',
                'manual_narration' => 'Expense amount paid for '.$expense->type->name.'- By Cash',
                'tx_type_id' => 1,
                'supplier_id' => $expense->getAttribute('supplier_id'),
                'company_id' => $expense->getAttribute('company_id')
            ], 'Expense', $isEdit);
        }
    }

    public function storeBankPayment(Expense $expense, $request)
    {
        $payment = new ExpensePayment();
        $payment->setAttribute('payment', $request->input('payment'));
        $payment->setAttribute('payment_date', $expense->getAttribute('expense_date'));
        $payment->setAttribute('payment_mode', 'Bank');
        $payment->setAttribute('notes', $request->input('notes'));
        $payment->setAttribute('paid_through', $request->input('paid_through'));
        $payment->setAttribute('prepared_by', auth()->id());
        $payment->setAttribute('expense_id', $expense->getAttribute('id'));
        $payment->setAttribute('company_id', $expense->getAttribute('company_id'));
        $payment->save();

        if($expense->getAttribute('approval_required') === 'No'){
            $this->recordBankPaymentTransaction($expense, $payment);
        }

        return $payment;
    }

    protected function recordBankPaymentTransaction(Expense $expense, ExpensePayment $expensePayment, $isEdit = false)
    {
        if($expense->getAttribute('expense_mode') === 'ForOthers')
        {
            /** Expense Account */
            $debitAccount1 = Account::where('id', $expense->getAttribute('expense_account'))
                ->first();

            $creditAccount1 = Account::where('account_type_id', 3)
                ->where('prefix', 'Company')
                ->where('accountable_id', $expense->getAttribute('company_id'))
                ->where('accountable_type', 'App\Company')
                ->first();

            recordTransaction($expense, $debitAccount1, $creditAccount1, [
                'date' => now()->toDateString(),
                'type' => 'Deposit',
                'amount' => $expensePayment->getAttribute('payment'),
                'auto_narration' => 'Expense amount paid for '.$expense->type->name.'- By Bank',
                'manual_narration' => 'Expense amount paid for '.$expense->type->name.'- By Bank',
                'tx_type_id' => 1,
                'supplier_id' => $expense->getAttribute('supplier_id'),
                'company_id' => $expense->getAttribute('company_id')
            ], 'Expense', $isEdit);

            /** second transaction */
            $debitAccount2 = Account::where('account_type_id', 3)
                ->where('prefix', 'Company')
                ->where('accountable_id', $expense->getAttribute('branch_id'))
                ->where('accountable_type', 'App\Company')
                ->first();

            $creditAccount2 = Account::find($expensePayment->getAttribute('paid_through'));

            recordTransaction($expense, $debitAccount2, $creditAccount2, [
                'date' => now()->toDateString(),
                'type' => 'Deposit',
                'amount' => $expensePayment->getAttribute('payment'),
                'auto_narration' => 'Expense amount paid for '.$expense->type->name.'- By Bank',
                'manual_narration' => 'Expense amount paid for '.$expense->type->name.'- By Bank',
                'tx_type_id' => 1,
                'supplier_id' => $expense->getAttribute('supplier_id'),
                'company_id' => $expense->getAttribute('company_id')
            ], 'Expense', $isEdit);
        }
        else{
            /** record transaction */
            $paidThrough = Account::where('id', $expensePayment->getAttribute('paid_through'))->first();

            if($paidThrough){
                $debitAccount = Account::find($expense->expense_account);
                $creditAccount = Account::find($paidThrough->id);
                recordTransaction($expensePayment, $debitAccount, $creditAccount, [
                    'date' => $expensePayment->getAttribute('payment_date'),
                    'type' => 'Deposit',
                    'amount' => $expensePayment->getAttribute('payment'),
                    'auto_narration' => 'Expense amount paid for '.$expense->type->name. 'by Bank',
                    'manual_narration' => 'Expense amount paid for '.$expense->type->name. 'by Bank',
                    'tx_type_id' => 1,
                    'supplier_id' => $expense->getAttribute('supplier_id'),
                    'company_id' => $expense->getAttribute('company_id')
                ], 'Expense', false);
            }
        }
    }

    public function storeOwnChequePayment(Expense $expense, $request)
    {
        $payment = new ExpensePayment();
        $payment->setAttribute('payment', $request->input('payment'));
        $payment->setAttribute('payment_date', $expense->getAttribute('expense_date'));
        $payment->setAttribute('payment_mode', 'Own Cheque');
        $payment->setAttribute('cheque_date', $request->input('cheque_date'));
        $payment->setAttribute('cheque_no', $request->input('cheque_no'));
        $payment->setAttribute('bank_id', $request->input('bank_id'));
        $payment->setAttribute('notes', $request->input('notes'));
        $payment->setAttribute('paid_through', $request->input('paid_through'));
        $payment->setAttribute('prepared_by', auth()->id());
        $payment->setAttribute('expense_id', $expense->getAttribute('id'));
        $payment->setAttribute('company_id', $expense->getAttribute('company_id'));
        $payment->save();

        $issuedCheque = new IssuedCheque();
        $issuedCheque->setAttribute('registered_date', $expense->getAttribute('expense_date'));
        $issuedCheque->setAttribute('amount', $request->input('payment'));
        $issuedCheque->setAttribute('cheque_date', $request->input('cheque_date'));
        $issuedCheque->setAttribute('cheque_no', $request->input('cheque_no'));
        $issuedCheque->setAttribute('bank_id', $request->input('bank_id'));
        $issuedCheque->setAttribute('chequeable_id', $expense->id);
        $issuedCheque->setAttribute('chequeable_type', 'App\ExpensePayment');
        $issuedCheque->setAttribute('supplier_id', $expense->getAttribute('supplier_id'));
        $issuedCheque->setAttribute('prepared_by', auth()->id());
        $issuedCheque->setAttribute('company_id', $expense->getAttribute('company_id'));
        $issuedCheque->save();

        if($expense->getAttribute('approval_required') === 'No'){
            $this->recordOwnChequePaymentTransaction($expense, $payment);
        }

        return $payment;
    }

    protected function recordOwnChequePaymentTransaction(Expense $expense, ExpensePayment $expensePayment, $isEdit = false)
    {
        if($expense->getAttribute('expense_mode') === 'ForOthers')
        {
            /** Expense Account */
            $debitAccount1 = Account::where('id', $expense->getAttribute('expense_account'))
                ->first();

            $creditAccount1 = Account::where('account_type_id', 3)
                ->where('prefix', 'Company')
                ->where('accountable_id', $expense->getAttribute('company_id'))
                ->where('accountable_type', 'App\Company')
                ->first();

            recordTransaction($expense, $debitAccount1, $creditAccount1, [
                'date' => now()->toDateString(),
                'type' => 'Deposit',
                'amount' => $expensePayment->getAttribute('payment'),
                'auto_narration' => 'Expense amount paid for '.$expense->type->name. 'by Own Cheque',
                'manual_narration' => 'Expense amount paid for '.$expense->type->name. 'by Own Cheque',
                'tx_type_id' => 1,
                'supplier_id' => $expense->getAttribute('supplier_id'),
                'company_id' => $expense->getAttribute('company_id')
            ], 'Expense', $isEdit);

            /** second transaction */
            $debitAccount2 = Account::where('account_type_id', 3)
                ->where('prefix', 'Company')
                ->where('accountable_id', $expense->getAttribute('branch_id'))
                ->where('accountable_type', 'App\Company')
                ->first();

            $creditAccount2 = Account::find($expensePayment->getAttribute('paid_through'));

            recordTransaction($expense, $debitAccount2, $creditAccount2, [
                'date' => now()->toDateString(),
                'type' => 'Deposit',
                'amount' => $expensePayment->getAttribute('payment'),
                'auto_narration' => 'Expense amount paid for '.$expense->type->name. 'by Own Cheque',
                'manual_narration' => 'Expense amount paid for '.$expense->type->name. 'by Own Cheque',
                'tx_type_id' => 1,
                'supplier_id' => $expense->getAttribute('supplier_id'),
                'company_id' => $expense->getAttribute('company_id')
            ], 'Expense', $isEdit);
        }
        else{
            $paidThrough = Account::where('id', $expensePayment->getAttribute('paid_through'))->first();

            if($paidThrough){
                $debitAccount = Account::find($expense->expense_account);
                $creditAccount = Account::find($paidThrough->id);
                recordTransaction($expensePayment, $debitAccount, $creditAccount, [
                    'date' => $expensePayment->getAttribute('payment_date'),
                    'type' => 'Deposit',
                    'amount' => $expensePayment->getAttribute('payment'),
                    'auto_narration' => 'Expense amount paid for '.$expense->type->name. 'by Own Cheque',
                    'manual_narration' => 'Expense amount paid for '.$expense->type->name. 'by Own Cheque',
                    'tx_type_id' => 1,
                    'supplier_id' => $expense->getAttribute('supplier_id'),
                    'company_id' => $expense->getAttribute('company_id')
                ], 'Expense', false);
            }
        }


    }

    public function storeThirdPartyChequePayment(Expense $expense, $request)
    {
        /** store payment details */
        $payment = new ExpensePayment();
        $payment->setAttribute('payment', $request->input('payment'));
        $payment->setAttribute('payment_date', $expense->getAttribute('expense_date'));
        $payment->setAttribute('payment_mode', 'Third Party Cheque');
        $payment->setAttribute('notes', $request->input('notes'));
        $payment->setAttribute('prepared_by', auth()->id());
        $payment->setAttribute('expense_id', $expense->getAttribute('id'));
        $payment->setAttribute('company_id', $expense->getAttribute('company_id'));
        $payment->save();

        /** store cheque details */
        $thirdPartyCheques = $request->input('cheques');
        foreach ($thirdPartyCheques as $thirdPartyCheque) {
            $chequeKey = chequeKeyToArray($thirdPartyCheque);
            $chequeHands = ChequeInHand::where('cheque_no', $chequeKey['cheque_no'])
                ->where('bank_id', $chequeKey['bank_id'])->get();

            foreach ($chequeHands as $chequeHand){
                $expCheque = new ExpenseCheque();
                $expCheque->setAttribute('expense_id', $expense->getAttribute('id'));
                $expCheque->setAttribute('cheque_in_hand_id', $chequeHand->id);
                $expCheque->setAttribute('expense_payment_id', $payment->getAttribute('id'));
                $expCheque->save();
            }
        }

        if($expense->getAttribute('approval_required') === 'No'){
            $this->recordThirdPartyChequePaymentTransaction($expense, $payment);
        }

        return $payment;
    }

    protected function recordThirdPartyChequePaymentTransaction(Expense $expense, ExpensePayment $expensePayment, $isEdit = false)
    {
        if($expense->getAttribute('expense_mode') === 'ForOthers')
        {
            /** Expense Account */
            $debitAccount1 = Account::where('id', $expense->getAttribute('expense_account'))
                ->first();

            $creditAccount1 = Account::where('account_type_id', 3)
                ->where('prefix', 'Company')
                ->where('accountable_id', $expense->getAttribute('company_id'))
                ->where('accountable_type', 'App\Company')
                ->first();

            recordTransaction($expense, $debitAccount1, $creditAccount1, [
                'date' => now()->toDateString(),
                'type' => 'Deposit',
                'amount' => $expensePayment->getAttribute('payment'),
                'auto_narration' => 'Expense amount paid for '.$expense->type->name. 'by Third Party Cheques',
                'manual_narration' => 'Expense amount paid for '.$expense->type->name. 'by Third Party Cheques',
                'tx_type_id' => 1,
                'supplier_id' => $expense->getAttribute('supplier_id'),
                'company_id' => $expense->getAttribute('company_id')
            ], 'Expense', $isEdit);

            /** second transaction */
            $debitAccount2 = Account::where('account_type_id', 3)
                ->where('prefix', 'Company')
                ->where('accountable_id', $expense->getAttribute('branch_id'))
                ->where('accountable_type', 'App\Company')
                ->first();

            $creditAccount2 = Account::where('account_type_id', 19)
                ->where('accountable_id', $expense->company_id)
                ->where('accountable_type', 'App\Company')
                ->where('prefix', 'CIH')
                ->first();

            recordTransaction($expense, $debitAccount2, $creditAccount2, [
                'date' => now()->toDateString(),
                'type' => 'Deposit',
                'amount' => $expensePayment->getAttribute('payment'),
                'auto_narration' => 'Expense amount paid for '.$expense->type->name. 'by Third Party Cheques',
                'manual_narration' => 'Expense amount paid for '.$expense->type->name. 'by Third Party Cheques',
                'tx_type_id' => 1,
                'supplier_id' => $expense->getAttribute('supplier_id'),
                'company_id' => $expense->getAttribute('company_id')
            ], 'Expense', $isEdit);
        }
        else{
            $paidThrough = Account::where('account_type_id', 19)
                ->where('accountable_id', $expense->company_id)
                ->where('accountable_type', 'App\Company')
                ->where('prefix', 'CIH')
                ->first();

            if($paidThrough) {
                $debitAccount = Account::find($expense->expense_account);
                $creditAccount = Account::find($paidThrough->id);
                recordTransaction($expensePayment, $debitAccount, $creditAccount, [
                    'date' => $expensePayment->getAttribute('payment_date'),
                    'type' => 'Deposit',
                    'amount' => $expensePayment->getAttribute('payment'),
                    'auto_narration' => 'Expense amount paid for ' . $expense->type->name . 'by Third Party Cheques',
                    'manual_narration' => 'Expense amount paid for ' . $expense->type->name . 'by Third Party Cheques',
                    'tx_type_id' => 1,
                    'company_id' => $expense->company_id,
                    'supplier_id' => $expense->supplier_id,
                    'customer_id' => $expense->customer_id,
                ], 'Expense', false);

                $expensePayment->setAttribute('paid_through', $paidThrough->id);
                $expensePayment->save();
            }
        }
    }

}