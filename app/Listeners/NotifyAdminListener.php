<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminListener
{
    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->order;

        $order_details = [
            'title' => 'Order information from this cool site',
            'description' => 'A new order has been completed!'
        ];

        \Mail::send('influencer.admin-email', ['order' => $order, 'orderdetails' => $order_details], function(Message $message) {
            $message->to('admin@admin.com');
            $message->from('admin@influencer_app.com');
            $message->subject('A new order has been completed!');
        });
    }
}
