<?php

use Illuminate\Database\Seeder;
use App\Department; 

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(RolesTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(CountryTableSeeder::class);
         $this->call(CompaniesTableSeeder::class);
         $this->call(DepartmentTableSeeder::class);
         $this->call(StoresTableSeeder::class);
         $this->call(AccountCategoriesTableSeeder::class);
         $this->call(AccountTypesTableSeeder::class);
         $this->call(AccountsTableSeeder::class);
         $this->call(StaffTableSeeder::class);
         $this->call(BusinessTypeTableSeeder::class);
         $this->call(MeasurementsTableSeeder::class);
         $this->call(ProductcategoriesTableSeeder::class);
         $this->call(ProductsTableSeeder::class);
         $this->call(UnitTypeSeeder::class);
         $this->call(PricebooksTableSeeder::class);
         $this->call(PricesTableSeeder::class);
         $this->call(TermsTableSeeder::class);
         $this->call(AddressesTableSeeder::class);
         $this->call(DocumentsTableSeeder::class);
         $this->call(CommentsTableSeeder::class);
         $this->call(VehicleTypeTableSeeder::class);
         $this->call(VehicleMakeSeeder::class);
         $this->call(VehicleModelSeeder::class);
         $this->call(VehiclesTableSeeder::class);
         $this->call(AuditLogTableSeeder::class);
         $this->call(SaleslocationsTableSeeder::class);
         $this->call(SupplierTableSeeder::class);
         $this->call(ProductionunitsTableSeeder::class);
         $this->call(RoutesTableSeeder::class);
         $this->call(LocationsTableSeeder::class);
         $this->call(CustomersTableSeeder::class);
         $this->call(MachinesTableSeeder::class);
         $this->call(RepsTableSeeder::class);
         $this->call(ReptargetsTableSeeder::class);
         $this->call(ContactpersonsTableSeeder::class);
         $this->call(EmailtemplatesTableSeeder::class);
         $this->call(StocksTableSeeder::class);
         $this->call(StockhistoriesTableSeeder::class);
         $this->call(PdftemplatesTableSeeder::class);
         $this->call(PuchaseOrderTableSeeder::class);
         $this->call(BanksTableSeeder::class);
         $this->call(GrnsTableSeeder::class);
         $this->call(BillsTableSeeder::class);
         $this->call(SuppliercreditsTableSeeder::class);
         $this->call(SuppliercreditrefundsTableSeeder::class);
         $this->call(DailysalesTableSeeder::class);
         $this->call(SalesordersTableSeeder::class);
         $this->call(InvoicesTableSeeder::class);
         $this->call(CustomercreditsTableSeeder::class);
         $this->call(CustomercreditrefundsTableSeeder::class);
         $this->call(RepvehiclehistoriesTableSeeder::class);
         $this->call(RoutetargetsTableSeeder::class);
         $this->call(InvoicepaymentsTableSeeder::class);
         $this->call(AllowancesTableSeeder::class);
         $this->call(AllowancehistoriesTableSeeder::class);
         $this->call(TransactionTypeTableSeeder::class);
         $this->call(TransactionsTableSeeder::class);
         $this->call(TransactionrecordsTableSeeder::class);

        //  -------------Try------------------------
        
        $this->call(EstimatesTableSeeder::class);
        $this->call(SalesinquiriesTableSeeder::class);
        $this->call(ExpenseTypeSeeder::class);
        $this->call(SaleshandoversTableSeeder::class);
        $this->call(SalesexpensesTableSeeder::class);
        $this->call(ExpensecategoriesTableSeeder::class);
        $this->call(ExpensereportsTableSeeder::class);
        $this->call(ExpensesTableSeeder::class);
        $this->call(ExpenseitemsTableSeeder::class);


        $this->call(ExpensereportreimbursesTableSeeder::class);
        $this->call(MileageratesTableSeeder::class);
        $this->call(TaxRatesTableSeeder::class);
        $this->call(DailysaleitemsTableSeeder::class);
        $this->call(DailysalecustomersTableSeeder::class);
        $this->call(CashbreakdownsTableSeeder::class);
        $this->call(ChequeinhandsTableSeeder::class);
        $this->call(SalesreturnsTableSeeder::class);


         $this->call(SalesreturnitemsTableSeeder::class);
         $this->call(VehiclerenewalsTableSeeder::class);
         $this->call(SalesreturnresolutionsTableSeeder::class);
         $this->call(SalesreturnreplacesTableSeeder::class);
         $this->call(DailysalesodoreadingsTableSeeder::class);
         $this->call(DailysalecreditordersTableSeeder::class);
         $this->call(SaleshandovershortagesTableSeeder::class);
         $this->call(SaleshandoverexcessesTableSeeder::class);
         $this->call(DesignationTableSeeder::class);
         $this->call(OpeningbalancereferencesTableSeeder::class);

         $this->call(BrandTableSeeder::class);
         $this->call(ShophandoversTableSeeder::class);
         $this->call(CashiershiftsTableSeeder::class);
         $this->call(AccountGroupsTableSeeder::class);
         $this->call(DailystocksTableSeeder::class);
         $this->call(DailystockitemsTableSeeder::class);
         $this->call(ApirequestsTableSeeder::class);
         $this->call(TransfersTableSeeder::class);
         $this->call(TransferitemsTableSeeder::class);
         $this->call(StocktransfersTableSeeder::class);

        $this->call(StocktransferitemsTableSeeder::class);
        $this->call(GrnitemsTableSeeder::class);
        $this->call(BillpaymentsTableSeeder::class);
        $this->call(ChequepaymentsTableSeeder::class);
        $this->call(SalescommissionsTableSeeder::class);
        $this->call(StockshortagesTableSeeder::class);
        $this->call(StockshortageitemsTableSeeder::class);
        $this->call(StockexcessesTableSeeder::class);
        $this->call(StockreviewsTableSeeder::class);
        $this->call(StockreviewitemsTableSeeder::class);


        $this->call(StockexcessitemsTableSeeder::class); 
        $this->call(PurchasereturnsTableSeeder::class);
        $this->call(PurchasereturnitemsTableSeeder::class);
        $this->call(ExpensepaymentsTableSeeder::class);
         $this->call(ExpensechequesTableSeeder::class);
        $this->call(IssuedchequesTableSeeder::class);
        $this->call(PurchaserequestsTableSeeder::class);
        $this->call(PurchaserequestitemsTableSeeder::class);
        $this->call(PricehistoriesTableSeeder::class);
        $this->call(PricehistoryitemsTableSeeder::class);

    }
}
