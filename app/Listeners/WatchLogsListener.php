<?php

namespace App\Listeners;

use App\Events\WatchLogsEvent;
use App\Models\HelpersModel\WatchHistoryHelper;
use App\Models\WatchHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class WatchLogsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WatchLogsEvent  $event
     * @return void
     */
    public function handle(WatchLogsEvent $event)
    {
        try {
            $option = isset($event->option) ? $event->option : [];
            if(isset($option['updateWatchHistory']) && $option['updateWatchHistory'] == true){
                Log::info("Start Watch Logs Event 1 - User_ID: " . $event->user->id);
                Log::info("Start Watch Logs Event 1 - watch_duration: " . $event->watch_duration);
                WatchHistoryHelper
                    ::updateWatchHistory($event->movieFindId, $event->user, $event->watch_duration );
            }
            if(isset($option['insertLogs']) && $option['insertLogs'] == true){
                Log::info("Start Watch Logs Event 2 - User_ID" . $event->user->id);
                Log::info('');
            }
        }catch (\Exception $ex){
            Log::info("Watch Logs Event: "  . $ex->getMessage());
        }
    }


}
