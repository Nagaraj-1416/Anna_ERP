<?php

use Illuminate\Database\Seeder;

class StockreviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stockreviews=[
            [
                'date' => '2024-02-01',
                'status' => 'Drafted',
                'notes' => 'stock reviews entry 1',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-01',
                'approved_by' => 1,
                'approved_on' => '2024-02-01',
                'store_id' => 1,
                'staff_id' => 1,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-02',
              
                'status' => 'Approved',
                'notes' => 'stock reviews entry 2',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-02',
                'approved_by' => 1,
                'approved_on' => '2024-02-02',
                'store_id' => 2,
                'staff_id' => 1,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-03',
           
                'status' => 'Drafted',
                'notes' => 'stock reviews entry 3',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-03',
                'approved_by' => 1,
                'approved_on' => '2024-02-03',
                'store_id' => 3,
                'staff_id' => 1,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-04',
         
                'status' => 'Approved',
                'notes' => 'stock reviews entry 4',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-04',
                'approved_by' => 1,
                'approved_on' => '2024-02-04',
                'store_id' => 1,
             
                'staff_id' => 1,
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-05',
           
                'status' => 'Approved',
                'notes' => 'stock reviews entry 5',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-05',
                'approved_by' => 1,
                'approved_on' => '2024-02-05',
                'store_id' => 2,
              
                'staff_id' => 1,
          
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-06',
               
                'status' => 'Drafted',
                'notes' => 'stock reviews entry 6',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-06',
                'approved_by' => 1,
                'approved_on' => '2024-02-06',
                'store_id' => 3,
            
                'staff_id' => 1,
              
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-07',
            
                'status' => 'Drafted',
                'notes' => 'stock reviews entry 7',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-07',
                'approved_by' => 1,
                'approved_on' => '2024-02-07',
                'store_id' => 1,
        
                'staff_id' => 1,
        
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-08',
            
                'status' => 'Approved',
                'notes' => 'stock reviews entry 8',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-08',
                'approved_by' => 1,
                'approved_on' => '2024-02-08',
                'store_id' => 2,
  
                'staff_id' => 1,
        
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-09',
             
                'status' => 'Drafted',
                'notes' => 'stock reviews entry 9',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-09',
                'approved_by' => 1,
                'approved_on' => '2024-02-09',
                'store_id' => 3,
       
                'staff_id' => 1,
  
                'company_id' => 1,
            ],
            [
                'date' => '2024-02-10',
            
                'status' => 'Approved',
                'notes' => 'stock reviews entry 10',
                'prepared_by' => 1,
                'prepared_on' => '2024-02-10',
                'approved_by' => 1,
                'approved_on' => '2024-02-10',
                'store_id' => 1,    
                'staff_id' => 1,
                'company_id' => 1,
            ],
        ];
        $now = \Carbon\Carbon::now();
            foreach ($stockreviews as $key => $transferitem) {
                $stockreviews[$key]['created_at'] = $now;
                $stockreviews[$key]['updated_at'] = $now;
            }
            \App\StockReview::insert($stockreviews);
    }
}