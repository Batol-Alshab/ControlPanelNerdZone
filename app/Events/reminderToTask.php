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
    // public function broadcastOn()
    //     {
    //         // return  ['my-channel'];

    //         return new Channel('my-channel');
    //     }
      public function broadcastAs()
      {
          return 'my-event';
      }

    public function broadcastOn()
    {
        // Broadcast on a private channel for the specific user
        return new Channel('user.' . $this->task->user_id);
    }


    // public $task;

    // public $userId;

    // public function __construct($userId, $task)
    // {
    //     $this->userId = $userId;
    //     $this->task = $task;
    // }

    // // نستخدم قناة خاصة لكل مستخدم
    // public function broadcastOn()
    // {
    //     return new PrivateChannel('reminders.' . $this->userId);
    // }

    // public function broadcastAs()
    // {
    //     return 'user-reminder';
    // }
}
