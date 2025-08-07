<?php

namespace App\Listeners;

use App\Events\SalesOrderCreated;
use App\Events\SalesOrderCreatedEvent;
use App\Models\SalesOrder;
use App\Services\PaymentMethodService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GeneratePaymentLinkListener
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
    public function handle(SalesOrderCreatedEvent $event): void
    {
        app(PaymentMethodService::class)
            ->getDriver(
                $event->sales_order->payment
            )
            ->proses(
                $event->sales_order
            );
    }
}
