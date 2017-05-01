<?php

namespace App\Console;

use App\Config;
use App\Console\Commands\PublishPosts;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Schema;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        PublishPosts::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if(Schema::hasTable('config') && Config::count()){
            $time = $this->getSchedulerTimings();
            $schedule->command('posts:publish')
                ->{$this->getSchedulerFrequency()}()
                ->timezone('Asia/Karachi')
                ->when(function () use ($time){
                    return date('H') >= $time['start'] && date('H') <= $time['end'];
                });
        }
    }

    /**
     * Get Post Frequency
     * @return mixed
     */
    protected function getSchedulerFrequency(){
        return Config::where('name', '=', 'scheduler_frequency')->first()->value;
    }

    /**
     * Get Scheduler Start/End Timings
     * @return array
     */
    protected function getSchedulerTimings(){
        return [
            'start' =>  Config::where('name', '=', 'scheduler_start_time')->first()->value,
            'end'   =>  Config::where('name', '=', 'scheduler_end_time')->first()->value
        ];
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
