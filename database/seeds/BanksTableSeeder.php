<?php

use Illuminate\Database\Seeder;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
            ['code' => '7010', 'name' => 'Bank of Ceylon'],
            ['code' => '7038', 'name' => 'Standard Chartered Bank'],
            ['code' => '7047', 'name' => 'Citi Bank'],
            ['code' => '7056', 'name' => 'Commercial Bank PLC'],
            ['code' => '7074', 'name' => 'Habib Bank Ltd'],
            ['code' => '7083', 'name' => 'Hatton National Bank PLC'],
            ['code' => '7092', 'name' => 'The Hongkong & Shanghai Banking Coporation Ltd'],
            ['code' => '7108', 'name' => 'Indian Bank'],
            ['code' => '7117', 'name' => 'Indian Overseas Bank'],
            ['code' => '7135', 'name' => 'Peoples Bank'],
            ['code' => '7144', 'name' => 'State Bank of India'],
            ['code' => '7162', 'name' => 'Nations Trust Bank PLC'],
            ['code' => '7205', 'name' => 'Deutsche Bank'],
            ['code' => '7214', 'name' => 'National Development Bank PLC'],
            ['code' => '7269', 'name' => 'MCB Bank Ltd'],
            ['code' => '7278', 'name' => 'Sampath Bank PLC'],
            ['code' => '7287', 'name' => 'Seylan Bank PLC'],
            ['code' => '7296', 'name' => 'Public Bank'],
            ['code' => '7302', 'name' => 'Union Bank of Colombo PLC'],
            ['code' => '7311', 'name' => 'Pan Asia Banking Corporation PLC'],
            ['code' => '7384', 'name' => 'ICICI Bank Ltd'],
            ['code' => '7454', 'name' => 'DFCC Vardhana Bank Ltd'],
            ['code' => '7463', 'name' => 'Amana Bank PLC'],
            ['code' => '7472', 'name' => 'Axis Bank'],
            ['code' => '7481', 'name' => 'Cargills Bank Limited'],
            ['code' => '7719', 'name' => 'National Savings Bank'],
            ['code' => '7728', 'name' => 'Sanasa Development Bank'],
            ['code' => '7737', 'name' => 'HDFC Bank'],
            ['code' => '7746', 'name' => 'Citizen Development Business Finance PLC'],
            ['code' => '7755', 'name' => 'Regional Development Bank'],
            ['code' => '7764', 'name' => 'State Mortgage & Investment Bank'],
            ['code' => '7773', 'name' => 'LB Finance PLC'],
            ['code' => '7782', 'name' => 'Senkadagala Finance PLC'],
            ['code' => '7807', 'name' => 'Commercial Leasing and Finance'],
            ['code' => '7816', 'name' => 'Vallibel Finance PLC'],
            ['code' => '7825', 'name' => 'Central Finance PLC'],
            ['code' => '7834', 'name' => 'Kanrich Finance Limited'],
            ['code' => '7852', 'name' => 'Alliance Finance Company PLC'],
            ['code' => '7861', 'name' => 'Lanka Orix Finance PLC'],
            ['code' => '7870', 'name' => 'Commercial Credit & Finance PLC'],
            ['code' => '7898', 'name' => 'Merchant Bank of Sri Lanka & Finance PLC'],
            ['code' => '7913', 'name' => 'Mercantile Investment and Finance PLC'],
            ['code' => '7922', 'name' => 'Peoples Leasing & Finance PLC'],
            ['code' => '8004', 'name' => 'Central Bank of Sri Lanka'],
        ];
        $now = \Carbon\Carbon::now();
        foreach ($banks as $key => $bank) {
            $banks[$key]['created_at'] = $now;
            $banks[$key]['updated_at'] = $now;
        }
        \App\Bank::insert($banks);
    }
}
