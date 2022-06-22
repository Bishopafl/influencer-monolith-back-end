<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\OrderResource;
use App\Order;
use Gate;
use Illuminate\Http\Request;
use Response;

/**
 *
 * just getting orders, nothing else...
 */

class OrderController
{
    public function index() {
        Gate::authorize('view', 'orders');

        $order = Order::paginate();

        return OrderResource::collection($order);
    }

    public function show($id) {
        Gate::authorize('view', 'orders');

        return new OrderResource(Order::find($id));
    }

    /**
     * Making a CSV file system via Laravel!
     */
    public function export() {
        Gate::authorize('view', 'orders');

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=orders.csv",
            "Pragma" => "no-cache",
            "Cache-COntrol" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];

        $callback = function() {
            $orders = Order::all();
            // fopen() | create a csv file to write with
            $file = fopen('php://output', 'w');

            // Header Row
            fputcsv($file, ['ID', 'Name', 'Email', 'Product Title', 'Price', 'Quantity']);

            // Body
            foreach($orders as $order) {
                fputcsv($file, [$order->id, $order->name, $order->email, '', '', '']);

                foreach($order->orderItems as $orderItem) {
                    fputcsv($file, ['', '', '', $orderItem->product_title, $orderItem->price, $orderItem->quantity]);
                }

            }
            // Close the file
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
     }
}
