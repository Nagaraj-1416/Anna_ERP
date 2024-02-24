<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = [
            [
                'comment'=>	'First Comment about the prouct',
                'user_id'=>	1,
                'parent_id'=>	1,
                'commentable_id'=>	1,
                'commentable_type'=>'Type for describe the comment',
            ],
            [
                'comment'=>	'Comment for describes the queries',
                'user_id'=>	1,
                'parent_id'=>	1,
                'commentable_id'=>	1,
                'commentable_type'=>'Type for describe the comment',
            ],
            [
                'comment'=>	'Third Comment about the prouct',
                'user_id'=>	1,
                'parent_id'=>	1,
                'commentable_id'=>	1,
                'commentable_type'=>'Type for describe the comment',
            ],
            [
                'comment'=>	'Fourth Comment about the prouct',
                'user_id'=>	1,
                'parent_id'=>	1,
                'commentable_id'=>	1,
                'commentable_type'=>'Type for describe the comment',
            ],
            [
                'comment'=>	'5 Comments to execute the comment section',
                'user_id'=>	1,
                'parent_id'=>	1,
                'commentable_id'=>	1,
                'commentable_type'=>'Type for describe the comment',
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($comments as $key => $comment) {
            $comments[$key]['created_at'] = $now;
            $comments[$key]['updated_at'] = $now;
        }

        \App\Comment::insert($comments);
    }
}
