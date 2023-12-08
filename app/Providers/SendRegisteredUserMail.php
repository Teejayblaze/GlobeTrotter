<?php

namespace App\Providers;

use App\Providers\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\SendMails;
use Illuminate\Support\Facades\Mail;

class SendRegisteredUserMail implements ShouldQueue
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
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        Mail::to($event->user->email)
            ->send(new SendMails($event->user, $event->mail_template, 'Email Verification', $event->group));
    }
}
