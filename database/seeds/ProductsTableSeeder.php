<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                "code"=>"PR0001",
                "barcode_number"=>"243234234234",
                "type"=>"Raw Material",
                "is_active"=>"Yes",
                "name"=>"Cotton",
                "tamil_name"=>"பருத்தி",
                "expense_account"=>1,
                "income_account"=>1,
                "inventory_account"=>1,
                "category_id"=>1,
                "measurement"=>"Nos",
                "min_stock_level"=>"56",
                "is_expirable"=>"Yes",
                "wholesale_price"=>null,
                "retail_price"=>null,
                "distribution_price"=>null,
                "packet_price"=>null,
                "buying_price"=>"345",
                "opening_cost"=>"34567",
                "opening_cost_at"=>"2024-02-01",
                "opening_qty"=>"435",
                "opening_qty_at"=>"2024-02-24",
                "notes"=>"sample notes entry for the product"
            ],
            [
                "code"=>"PR0002",
                "barcode_number"=>"243234234234",
                "type"=>"Raw Material",
                "is_active"=>"Yes",
                "name"=>"Steel",
                "tamil_name"=>"இரும்பு",
                "expense_account"=>1,
                "income_account"=>1,
                "inventory_account"=>1,
                "category_id"=>1,
                "measurement"=>"Nos",
                "min_stock_level"=>"56",
                "is_expirable"=>"Yes",
                "wholesale_price"=>null,
                "retail_price"=>null,
                "distribution_price"=>null,
                "packet_price"=>null,
                "buying_price"=>"345",
                "opening_cost"=>"34567",
                "opening_cost_at"=>"2024-02-01",
                "opening_qty"=>"435",
                "opening_qty_at"=>"2024-02-24",
                "notes"=>"sample notes entry for the product"
            ],
            [
                "code"=>"PR0003",
                "barcode_number"=>"243234234234",
                "type"=>"Raw Material",
                "is_active"=>"Yes",
                "name"=>"Plastic",
                "tamil_name"=>"நெகிழி",
                "expense_account"=>1,
                "income_account"=>1,
                "inventory_account"=>1,
                "category_id"=>1,
                "measurement"=>"Nos",
                "min_stock_level"=>"56",
                "is_expirable"=>"Yes",
                "wholesale_price"=>null,
                "retail_price"=>null,
                "distribution_price"=>null,
                "packet_price"=>null,
                "buying_price"=>"345",
                "opening_cost"=>"34567",
                "opening_cost_at"=>"2024-02-01",
                "opening_qty"=>"435",
                "opening_qty_at"=>"2024-02-24",
                "notes"=>"sample notes entry for the product"
            ]
        ];
        $now = \Carbon\Carbon::now();
        foreach ($products as $key => $product) {
            $products[$key]['created_at'] = $now;
            $products[$key]['updated_at'] = $now;
        }

        \App\Product::insert($products);
       
    }
}
