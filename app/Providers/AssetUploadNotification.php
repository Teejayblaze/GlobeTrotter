<?php

namespace App\Providers;

use App\Providers\ActivityEventNotifier;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssetUploadNotification implements ShouldQueue
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
     * @param  ActivityEventNotifier  $event
     * @return void
     */
    public function handle(ActivityEventNotifier $event)
    {
        //
        return true;
    }
}
