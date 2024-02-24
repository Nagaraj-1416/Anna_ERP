<?php

use Illuminate\Database\Seeder;

class DocumentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documents = [
            [  	
                'name'=>'Cottom Rawmaterial',
                'mime'=>'700',
                'extension'=>	'.png',
                'size'=>	'566kb',
                'documentable_type'=>'Image',
                'documentable_id'=>	1,
                'user_id'=>	1,
            ],
            [  	
                'name'=>'Steel Rawmaterial',
                'mime'=>'700',
                'extension'=>	'.png',
                'size'=>	'566kb',
                'documentable_type'=>'Image',
                'documentable_id'=>	1,
                'user_id'=>	1,
            ],
            [  	
                'name'=>'Sandal Wood',
                'mime'=>'700',
                'extension'=>	'.png',
                'size'=>	'566kb',
                'documentable_type'=>'Image',
                'documentable_id'=>	1,
                'user_id'=>	1,
            ],
            [  	
                'name'=>'Metal Sheet 1mtr',
                'mime'=>'700',
                'extension'=>	'.png',
                'size'=>	'566kb',
                'documentable_type'=>'Image',
                'documentable_id'=>	1,
                'user_id'=>	1,
            ],
            [  	
                'name'=>'Paper wastes',
                'mime'=>'700',
                'extension'=>	'.png',
                'size'=>	'566kb',
                'documentable_type'=>'Image',
                'documentable_id'=>	1,
                'user_id'=>	1,
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($documents as $key => $brand) {
            $documents[$key]['created_at'] = $now;
            $documents[$key]['updated_at'] = $now;
        }

        \App\Document::insert($documents);
    }
}
