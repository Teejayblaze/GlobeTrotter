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

class ActivityEventNotifier implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $channelName;
    public $eventName;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($channel, $event, $data)
    {
        $this->channelName = $channel;
        $this->eventName = $event;
        $this->data = $data; 
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->channelName);
    }

    public function broadcastAs()
    {
        return  $this->eventName;
    }

    public function broadcastWith()
    {
        return ['message' => $this->data];
    }
}
