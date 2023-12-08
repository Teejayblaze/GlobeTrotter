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

class UserRegistered implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $mail_template;
    public $group;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $mail_template = 'advertiser.mails.signup', $group = 'advertiser')
    {
        $this->user = $user;
        $this->mail_template = $mail_template;
        $this->group = $group;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('new-user');
    }

    public function broadcastAs()
    {
        return 'user-signup';
    }

    public function broadcastWith()
    {
        return ['msg' => $this->user->lastname . ' has just registered successfully.'];
    }
}
