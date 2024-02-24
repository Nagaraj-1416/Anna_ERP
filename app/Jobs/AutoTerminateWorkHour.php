<?php

namespace App\Jobs;

use App\WorkHour;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AutoTerminateWorkHour implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {

    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        $currentDate = carbon()->now()->tz('Asia/Colombo')->toDateString();
        $currentTime = carbon()->now()->tz('Asia/Colombo')->toTimeString();

        $workHours = WorkHour::where('date', $currentDate)->where('end', '<=', $currentTime)
            ->where('status', 'Allocated')->get();
        if(count($workHours)){
            $workHours->each(function (WorkHour $workHour) {
                $workHour->status = 'Terminated';
                $workHour->save();
            });
            $this->info('Done!');
        }else{
            $this->info('No running allocation found!');
        }
    }
}
