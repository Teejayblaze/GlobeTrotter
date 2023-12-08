<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserAssetBookingPaymentReminderMail extends Mailable
{
    use Queueable, SerializesModels;
    private $events;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($events)
    {
        $this->events = $events;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->events->user->name ? $this->events->user->name : $this->events->user->lastname;

        return $this->view('asset_booking_reminder')
            ->subject('Pending Payment for ' . $this->events->assetBooking->asset->name . ' Asset.')
            ->from(config('app.from'))
            ->with([
                'name' => $name, 
                'grace_hrs' => $this->events->grace_hrs, 
                'booked_asset_name' => $this->events->assetBooking->asset->name
            ]);
    }
}
