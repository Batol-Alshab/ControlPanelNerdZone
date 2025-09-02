<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class reminderToTask implements ShouldBroadcastNow// ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

        public $task;

      public function __construct($task)
      {
          $this->task = $task;
      }
      public function broadcastAs()
      {
          return 'reminderToTask';
      }

    public function broadcastOn()
    {
        return new Channel('user.' . $this->task->user_id);
    }

}
