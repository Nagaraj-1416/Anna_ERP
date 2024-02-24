<?php

namespace App\Console\Commands;

use App\WorkHour;
use Illuminate\Console\Command;

class AutoTerminateWorkHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:terminate:work:hour';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Terminate allocated work hours if the time passed';

    /**
     * AutoTerminateWorkHours constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
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
