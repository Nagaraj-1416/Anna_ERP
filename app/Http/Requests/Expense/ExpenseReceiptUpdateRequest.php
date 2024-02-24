<?php

namespace App\Http\Requests\Expense;

use App\{Account, BusinessType, Company, Customer, ExpenseType, Staff, Supplier};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ExpenseReceiptUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'type_id' => 'required',
            'expense_date' => 'required|date',
            'expense_time' => 'required|date_format:H:i:s',
            'notes' => 'required',
            'amount' => 'required|numeric'
        ];

        /*if ($this->input('cheque_type') != 'Own') {
            $rules['paid_through'] = 'required';
        }

        if ($this->input('cheque_type') == 'Third Party') {
            $rules['third_party_cheques'] = 'required';
        }*/

        /*if ($this->input('payment_mode') == 'Cheque' && $this->input('cheque_type') == 'Own') {
            $rules['cheque_no'] = 'required';
            $rules['cheque_date'] = 'required|date|after_or_equal:' . carbon();
            $rules['cheque_bank_id'] = 'required|exists:banks,id';
        }

        if ($this->input('payment_mode') == 'Direct Deposit') {
            $rules['account_no'] = 'required';
            $rules['deposited_date'] = 'required|date';
            $rules['dd_bank_id'] = 'required|exists:banks,id';
        }

        if ($this->input('payment_mode') == 'Credit Card') {
            $rules['card_holder_name'] = 'required';
            $rules['card_no'] = 'required|min:16';
            $rules['expiry_date'] = 'required|date|after_or_equal:' . carbon();
            $rules['cc_bank_id'] = 'required';
        }*/

        /** expenses type validation */

        // Salary, Salary Advance, Bonus, Commission
        // EPF, ETF, NBT, Vat, Rent
        $monthFieldTypes = ['12', '13', '11', '15', '20', '21', '22', '23', '33'];
        if(in_array($this->input('type_id'), $monthFieldTypes)){
            $rules['month'] = 'required';
        }

        // Salary, Salary Advance, Bonus, Loan -->
        // Commission, Allowance, Fine, Transport -->
        $staffFieldType = ['12', '13', '11', '14', '15', '3', '9', '8'];
        if(in_array($this->input('type_id'), $staffFieldType)){
            $rules['staff_id'] = 'required';
        }

        // Loan -->
        $installPeriodFieldType = ['14'];
        if(in_array($this->input('type_id'), $installPeriodFieldType)){
            $rules['installment_period'] = 'required';
        }

        // Allowance -->
        $daysFieldType = ['3'];
        if(in_array($this->input('type_id'), $daysFieldType)){
            $rules['no_of_days'] = 'required';
        }

        // Vehicle Repair, Fuel, Service, Parking, Vehicle Maintenance, Lease, Fine, transport -->
        $vehicleFieldType = ['6', '2', '5', '17', '18', '9', '8', '16'];
        if(in_array($this->input('type_id'), $vehicleFieldType)){
            $rules['vehicle_id'] = 'required';
        }

        // Fuel -->
        $literFieldType = ['2'];
        if(in_array($this->input('type_id'), $literFieldType)){
            $rules['liter'] = 'required';
        }

        // Fuel -->
        $odometerFieldType = ['2'];
        if(in_array($this->input('type_id'), $odometerFieldType)){
            $rules['odometer'] = 'required';
        }

        // Vehicle Repair -->
        $whatRepairedFieldType = ['6'];
        if(in_array($this->input('type_id'), $whatRepairedFieldType)){
            $rules['what_was_repaired'] = 'required';
        }

        // Vehicle Repair, Service, Machine Maintenance -->
        $changedItemFieldType = ['6', '16', '29'];
        if(in_array($this->input('type_id'), $changedItemFieldType)){
            $rules['changed_item'] = 'required';
        }

        // Vehicle Repair, Machine Maintenance -->
        $supplierFieldType = ['6', '29'];
        if(in_array($this->input('type_id'), $supplierFieldType)){
            $rules['supplier_id'] = 'required';
        }

        // Vehicle & Machine Repair, Machine Maintenance -->
        $expiryDateFieldType = ['6', '29'];
        if(in_array($this->input('type_id'), $expiryDateFieldType)){
            $rules['repair_expiry_date'] = 'required';
        }

        // Vehicle & Machine Repair, Machine Maintenance -->
        $repairingShopFieldType = ['6', '29'];
        if(in_array($this->input('type_id'), $repairingShopFieldType)){
            $rules['repairing_shop'] = 'required';
        }

        // Vehicle, Machine Repair, Service, Machine Maintenance -->
        $labourChargeFieldType = ['6', '16', '29'];
        if(in_array($this->input('type_id'), $labourChargeFieldType)){
            $rules['labour_charge'] = 'required';
        }

        // Vehicle Repair, Service -->
        $driverFieldType = ['6', '16'];
        if(in_array($this->input('type_id'), $driverFieldType)){
            $rules['driver_id'] = 'required';
        }

        // Vehicle Repair -->
        $odoAtRepairFieldType = ['6'];
        if(in_array($this->input('type_id'), $odoAtRepairFieldType)){
            $rules['odo_at_repair'] = 'required';
        }

        // Service -->
        $serviceStationFieldType = ['16'];
        if(in_array($this->input('type_id'), $serviceStationFieldType)){
            $rules['service_station'] = 'required';
        }

        // Service -->
        $odoAtServiceFieldType = ['16'];
        if(in_array($this->input('type_id'), $odoAtServiceFieldType)){
            $rules['odo_at_service'] = 'required';
        }

        // Parking -->
        $parkingNameFieldType = ['5'];
        if(in_array($this->input('type_id'), $parkingNameFieldType)){
            $rules['parking_name'] = 'required';
        }

        // Vehicle Maintenance -->
        $vehicleMainTypeFieldType = ['17'];
        if(in_array($this->input('type_id'), $vehicleMainTypeFieldType)){
            $rules['vehicle_maintenance_type'] = 'required';
        }

        // Vehicle Maintenance, Income Tax, CEB, Telephone, Room Charge, Water -->
        $fromDateFieldType = ['17', '24', '25', '27', '7', '26'];
        if(in_array($this->input('type_id'), $fromDateFieldType)){
            $rules['from_date'] = 'required';
        }

        // Vehicle Maintenance, Income Tax, CEB, Telephone, Room Charge, Water -->
        $toDateFieldType = ['17', '24', '25', '27', '7', '26'];
        if(in_array($this->input('type_id'), $toDateFieldType)){
            $rules['to_date'] = 'required';
        }

        // Lease -->
        $noMonthsFieldType = ['18'];
        if(in_array($this->input('type_id'), $noMonthsFieldType)){
            $rules['no_of_months'] = 'required';
        }

        // Fine -->
        $fineFieldType = ['9'];
        if(in_array($this->input('type_id'), $fineFieldType)){
            $rules['fine_reason'] = 'required';
        }

        // Transport -->
        $fromDestinationFieldType = ['8'];
        if(in_array($this->input('type_id'), $fromDestinationFieldType)){
            $rules['from_destination'] = 'required';
        }

        // Transport -->
        $toDestinationFieldType = ['8'];
        if(in_array($this->input('type_id'), $toDestinationFieldType)){
            $rules['to_destination'] = 'required';
        }

        // Transport -->
        $noOfBagsFieldType = ['8'];
        if(in_array($this->input('type_id'), $noOfBagsFieldType)){
            $rules['no_of_bags'] = 'required';
        }

        // CEB, Telephone, Water -->
        $accountNumberFieldType = ['25', '27', '26'];
        if(in_array($this->input('type_id'), $accountNumberFieldType)){
            $rules['account_number'] = 'required';
        }

        // CEB, Water -->
        $unitsReadingFieldType = ['25', '26'];
        if(in_array($this->input('type_id'), $unitsReadingFieldType)){
            $rules['units_reading'] = 'required';
        }

        // Machine Maintenance -->
        $machineFieldType = ['29'];
        if(in_array($this->input('type_id'), $machineFieldType)){
            $rules['machine'] = 'required';
        }

        // Festival Expense -->
        $festivalNameFieldType = ['31'];
        if(in_array($this->input('type_id'), $festivalNameFieldType)){
            $rules['festival_name'] = 'required';
        }

        // Donation -->
        $donatedToFieldType = ['32'];
        if(in_array($this->input('type_id'), $donatedToFieldType)){
            $rules['donated_to'] = 'required';
        }

        // Donation -->
        $donatedReasonFieldType = ['32'];
        if(in_array($this->input('type_id'), $donatedReasonFieldType)){
            $rules['donated_reason'] = 'required';
        }

        // Room Charge -->
        $hotelNameFieldType = ['7'];
        if(in_array($this->input('type_id'), $hotelNameFieldType)){
            $rules['hotel_name'] = 'required';
        }

        // OD Interest, CHQ Book Issue -->
        $bankNumberFieldType = ['34', '35'];
        if(in_array($this->input('type_id'), $bankNumberFieldType)){
            $rules['bank_number'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'company_id.required' => 'The company field is required.',
            'notes.required' => 'Expense narration field is required.',
        ];
        return $messages;
    }

}
