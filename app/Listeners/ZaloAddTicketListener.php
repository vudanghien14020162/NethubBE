<?php

namespace App\Listeners;

use App\Events\ZaloAddTicketEvent;
use App\Models\EventUserTicket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ZaloAddTicketListener
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
     * @param  \App\Events\ZaloAddTicketEvent  $event
     * @return void
     */
    public function handle(ZaloAddTicketEvent $event)
    {

    }
}
