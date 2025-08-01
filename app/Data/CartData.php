<?php
declare(strict_types=1);
namespace App\Data;

use Spatie\LaravelData\Data;
use Illuminate\Support\Number;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\DataCollectionOf;

class CartData extends Data
{
    public float $total;
    public int $total_quantity;
    public int $total_weight;
    public string $total_formatted;
    public function __construct(
        #[DataCollectionOf(CartItemData::class)]
        public DataCollection $items
    ) {
        $items = $items->toCollection();
        $this->total = $items->sum(fn(CartItemData $item) => $item->price * $item->quantity);
        $this->total_quantity = $items->sum(fn(CartItemData $item) => $item->quantity);
        $this->total_weight = $items->sum(fn(CartItemData $item) => $item->weight ?? 0);
        $this->total_formatted = Number::currency($this->total);
    }
}
