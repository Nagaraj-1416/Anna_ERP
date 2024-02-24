<?php

use Illuminate\Database\Seeder;

class AuditLogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $auditlogs = [
            [
                'log_name' => 'Log name 1',
                'description' => 'Description for the log',
                'subject_id' => 1,
                'subject_type' => 'Log type 1',
                'causer_type' => 1,
                'properties' => 'Log properties enter for the log entry',
                'ip' => '127.0.566.8',
                'latitude' => '40°45 N',
                'original_latitude' => '40°45 N',
                'longitude' => '40°45 N',
                'original_longitude' => '40°45 N',
            ],
            [
                'log_name' => 'Log name 2',
                'description' => 'Description for the log',
                'subject_id' => 1,
                'subject_type' => 'Log type 2',
                'causer_type' => 1,
                'properties' => 'Log properties enter for the log entry',
                'ip' => '127.0.566.8',
                'latitude' => '40°45 N',
                'original_latitude' => '40°45 N',
                'longitude' => '40°45 N',
                'original_longitude' => '40°45 N',
            ],
            [
                'log_name' => 'Log name 3',
                'description' => 'Description for the log',
                'subject_id' => 1,
                'subject_type' => 'Log type 3',
                'causer_type' => 1,
                'properties' => 'Log properties enter for the log entry',
                'ip' => '127.0.566.8',
                'latitude' => '40°45 N',
                'original_latitude' => '40°45 N',
                'longitude' => '40°45 N',
                'original_longitude' => '40°45 N',
            ],
            [
                'log_name' => 'Log name 4',
                'description' => 'Description for the log',
                'subject_id' => 1,
                'subject_type' => 'Log type 4',
                'causer_type' => 1,
                'properties' => 'Log properties enter for the log entry',
                'ip' => '127.0.566.8',
                'latitude' => '40°45 N',
                'original_latitude' => '40°45 N',
                'longitude' => '40°45 N',
                'original_longitude' => '40°45 N',
            ],
            [
                'log_name' => 'Log name 5',
                'description' => 'Description for the log',
                'subject_id' => 1,
                'subject_type' => 'Log type 5',
                'causer_type' => 1,
                'properties' => 'Log properties enter for the log entry',
                'ip' => '127.0.566.8',
                'latitude' => '40°45 N',
                'original_latitude' => '40°45 N',
                'longitude' => '40°45 N',
                'original_longitude' => '40°45 N',
            ],
          
        ];

        $now = \Carbon\Carbon::now();
        foreach ($auditlogs as $key => $auditlog) {
            $auditlogs[$key]['created_at'] = $now;
            $auditlogs[$key]['updated_at'] = $now;
        }

        // \App\VehicleMake::insert($auditlogs);
    }
}
