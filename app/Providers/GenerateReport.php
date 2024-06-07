<?php

namespace App\Providers;

use App\Models\Report;
use App\Providers\SaleConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateReport implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SaleConfirmed $event): void
    {
        $order = $event->order;

//        $customerName = $order->customer->first_name.' '.$order->customer->last_name;

        $lines = $order->productLines;

        foreach ($lines as $line) {
            $report = Report::create([
                'order_id' => $order->id,
                'customer_name' => 'Test Name',
                'product_code' => $line->identifier,
                'amount' => $line->sub_total,
                'quantity' => $line->quantity,
            ]);
        }

    }
}
