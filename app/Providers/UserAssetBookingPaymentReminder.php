<?php

namespace App\Providers;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserAssetBookingPaymentReminder implements ShouldBroadcastNow, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $assetBooking;
    public $grace_hrs;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($assetBooking, $grace_hrs, $user)
    {
        $this->assetBooking = $assetBooking;
        $this->grace_hrs = $grace_hrs;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('assetbooking-payment-reminder');
    }

    public function broadcastAs()
    {
        return 'assetbooking-payment-reminder';
    }

    public function broadcastWith()
    {
        return [
            'assetbooking' => $this->assetBooking,
            'grace_hrs' => $this->grace_hrs,
            'user' => $this->user,
        ];
    }
}
