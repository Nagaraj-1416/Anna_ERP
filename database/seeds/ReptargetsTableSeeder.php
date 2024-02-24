<?php

use Illuminate\Database\Seeder;

class ReptargetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rep_targets = [
            [       
            'type'=>	'Yearly',
            'start_date'=>	'2024-02-08',
            'end_date'=>	'2024-03-08',
            'target'=>	'sample target to rep',
            'achieved'=> 'sample achieved 1',
            'rep_id'=> 1,
            'is_active'=> 'Yes',
            ],
            [       
            'type'=>	'Monthly',
            'start_date'=>	'2024-04-08',
            'end_date'=>	'2024-04-08',
            'target'=>	'sample target to rep',
            'achieved'=> 'sample achieved 1',
            'rep_id'=> 1,
            'is_active'=> 'Yes',
            ],
            [       
            'type'=>	'Weekly',
            'start_date'=>	'2024-04-08',
            'end_date'=>	'2024-05-08',
            'target'=>	'sample target to rep',
            'achieved'=> 'sample achieved 1',
            'rep_id'=> 1,
            'is_active'=> 'Yes',
            ],
            [       
            'type'=>	'Weekly',
            'start_date'=>	'2024-02-08',
            'end_date'=>	'2024-03-08',
            'target'=>	'sample target to rep',
            'achieved'=> 'sample achieved 1',
            'rep_id'=> 1,
            'is_active'=> 'Yes',
            ],
            [       
            'type'=>	'Weekly',
            'start_date'=>	'2024-02-08',
            'end_date'=>	'2024-03-08',
            'target'=>	'sample target to rep',
            'achieved'=> 'sample achieved 1',
            'rep_id'=> 1,
            'is_active'=> 'Yes',
            ],
            [       
            'type'=>	'Monthly',
            'start_date'=>	'2024-01-08',
            'end_date'=>	'2024-03-08',
            'target'=>	'sample target to rep',
            'achieved'=> 'sample achieved 1',
            'rep_id'=> 1,
            'is_active'=> 'Yes',
            ],
            [       
            'type'=>	'Yearly',
            'start_date'=>	'2024-02-08',
            'end_date'=>	'2024-03-08',
            'target'=>	'sample target to rep',
            'achieved'=> 'sample achieved 1',
            'rep_id'=> 1,
            'is_active'=> 'Yes',
            ],
            [       
            'type'=>	'Yearly',
            'start_date'=>	'2024-02-08',
            'end_date'=>	'2024-03-08',
            'target'=>	'sample target to rep',
            'achieved'=> 'sample achieved 1',
            'rep_id'=> 1,
            'is_active'=> 'Yes',
            ],
            [       
            'type'=>	'Daily',
            'start_date'=>	'2024-02-08',
            'end_date'=>	'2024-03-08',
            'target'=>	'sample target to rep',
            'achieved'=> 'sample achieved 1',
            'rep_id'=> 1,
            'is_active'=> 'Yes',
            ],
            [       
            'type'=>	'Yearly',
            'start_date'=>	'2024-02-08',
            'end_date'=>	'2024-03-08',
            'target'=>	'sample target to rep',
            'achieved'=> 'sample achieved 1',
            'rep_id'=> 1,
            'is_active'=> 'Yes',
            ],
           
        ];

        $now = \Carbon\Carbon::now();
        foreach ($rep_targets as $key => $rep_target) {
            $rep_targets[$key]['created_at'] = $now;
            $rep_targets[$key]['updated_at'] = $now;
        }

        \App\RepTarget::insert($rep_targets);
    }
}
