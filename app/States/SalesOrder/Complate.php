<?php
declare(strict_types=1);
namespace App\States\SalesOrder;
class Complate extends SalesOrderState
{
    public function label() : string
    {
        return "Selesai";
    }
}