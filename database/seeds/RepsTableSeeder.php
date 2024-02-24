<?php

use Illuminate\Database\Seeder;

class RepsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reps = [
            [       
            'code'=>	'0001',
            'name'=>	'Rep 1',
            'notes'=>	'notes for the entry for the rep',
            'staff_id'=>	1,
            'vehicle_id'=>	1,
            'cl_amount'=>	454.34,
            'cl_notify_rate'=>	434445.43,
            'is_active'=>	1,
            'company_id'=>	1,
            ],
            [       
                'code'=>	'0002',
            'name'=>	'Rep 2',
            'notes'=>	'notes for the entry for the rep',
            'staff_id'=>	1,
            'vehicle_id'=>	1,
            'cl_amount'=>	454.34,
            'cl_notify_rate'=>	434445.43,
            'is_active'=>	1,
            'company_id'=>	1,
            ],
            [       
                'code'=>	'0003',
            'name'=>	'Rep 3',
            'notes'=>	'notes for the entry for the rep',
            'staff_id'=>	1,
            'vehicle_id'=>	1,
            'cl_amount'=>	454.34,
            'cl_notify_rate'=>	434445.43,
            'is_active'=>	1,
            'company_id'=>	1,
            ],
            [       
                'code'=>	'0004',
            'name'=>	'Rep 4',
            'notes'=>	'notes for the entry for the rep',
            'staff_id'=>	1,
            'vehicle_id'=>	1,
            'cl_amount'=>	454.34,
            'cl_notify_rate'=>	434445.43,
            'is_active'=>	1,
            'company_id'=>	1,
            ],
            [       
                'code'=>	'0005',
            'name'=>	'Rep 5',
            'notes'=>	'notes for the entry for the rep',
            'staff_id'=>	1,
            'vehicle_id'=>	1,
            'cl_amount'=>	454.34,
            'cl_notify_rate'=>	434445.43,
            'is_active'=>	1,
            'company_id'=>	1,
            ],
            [       
                'code'=>	'0006',
            'name'=>	'Rep 6',
            'notes'=>	'notes for the entry for the rep',
            'staff_id'=>	1,
            'vehicle_id'=>	1,
            'cl_amount'=>	454.34,
            'cl_notify_rate'=>	434445.43,
            'is_active'=>	1,
            'company_id'=>	1,
            ],
            [       
                'code'=>	'0007',
            'name'=>	'Rep 7',
            'notes'=>	'notes for the entry for the rep',
            'staff_id'=>	1,
            'vehicle_id'=>	1,
            'cl_amount'=>	454.34,
            'cl_notify_rate'=>	434445.43,
            'is_active'=>	1,
            'company_id'=>	1,
            ],
            [       
                'code'=>	'0008',
            'name'=>	'Rep 8',
            'notes'=>	'notes for the entry for the rep',
            'staff_id'=>	1,
            'vehicle_id'=>	1,
            'cl_amount'=>	454.34,
            'cl_notify_rate'=>	434445.43,
            'is_active'=>	1,
            'company_id'=>	1,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($reps as $key => $brand) {
            $reps[$key]['created_at'] = $now;
            $reps[$key]['updated_at'] = $now;
        }

        \App\Rep::insert($reps);
    }
}
