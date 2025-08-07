<?php

namespace App\Listeners;

use App\Events\SalesOrderCancelledEvent;
use App\Events\SalesOrderCompletedEvent;
use App\Mail\SalesOrderComplatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SalesOrderCompeletedListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SalesOrderCompletedEvent $event): void
    {
        Mail::queue(
            new SalesOrderComplatedMail($event->sales_order)
        );
    }
}
