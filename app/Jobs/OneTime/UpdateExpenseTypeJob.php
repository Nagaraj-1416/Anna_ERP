<?php

namespace App\Jobs\OneTime;

use App\Expense;
use App\SalesExpense;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Schema;

class UpdateExpenseTypeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->updateExpenses();
        $this->updateSalesExpenses();
        echo('Done');
    }

    protected function updateExpenses()
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (Schema::hasColumn('expenses', 'expense_type')) {
                $expenses = Expense::all();
                $this->updateData($expenses);
                $table->dropColumn('expense_type');
            }
        });
    }

    protected function updateSalesExpenses()
    {
        Schema::table('sales_expenses', function (Blueprint $table) {
            if (Schema::hasColumn('sales_expenses', 'expense_type')) {
                $expenses = SalesExpense::all();
                $this->updateData($expenses);
                $table->dropColumn('expense_type');
            }
        });
    }

    protected function updateData($expenses){
        foreach ($expenses as $expense){
            if ($expense->type_id) continue;
            $expense->update([
                'type_id' => $this->getTypeId($expense->expense_type)
            ]);
        }
    }

    protected function getTypeId($type)
    {
        $typeId = 4;
        switch ($type){
            case 'General':
                $typeId = generalTypeId();
                break;
            case 'Mileage':
                $typeId = mileageTypeId();
                break;
            case 'Fuel':
                $typeId = fuelTypeId();
                break;
            case 'Allowance':
                $typeId = allowanceTypeId();
                break;
        }
        return $typeId;
    }
}
