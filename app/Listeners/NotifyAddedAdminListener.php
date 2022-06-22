<?php

namespace App\Listeners;

use App\Events\AdminAddedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAddedAdminListener
{
    public function handle(AdminAddedEvent $event)
    {
        $user = $event->user;

        $order = $event->order;

        $order_details = [
            'title' => 'Order information from this cool site',
            'description' => 'A new order has been completed!'
        ];

        \Mail::send('admin.adminAdded', [], function(Message $message) use ($user) {
            $message->to($user->email);
            $message->from('admin@influencer_app.com');
            $message->subject('You have been added to the Influencer Admin App!');
        });
    }
}
