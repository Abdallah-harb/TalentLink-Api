<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendNotifEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id,$message,$title;
    public function __construct($data)
    {
       $this->user_id = $data['user_id'];
       $this->message = $data['message'];
       $this->title = $data['title'];
    }

    public function broadcastOn(): array
    {
        return[
            new PrivateChannel('end-course.'.$this->user_id)
        ] ;
    }
}
