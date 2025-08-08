<?php

namespace App\Models;

use App\States\SalesOrder\SalesOrderState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\ModelStates\HasStates;

class SalesOrder extends Model
{
    use HasStates, LogsActivity;
    protected $with = ['items'];
    protected $casts = [
        'payment_payload' => 'json',
        'status' => SalesOrderState::class
    ];

    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }
     public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['status', 'total']);
        // Chain fluent methods for configuration options
    }
}
