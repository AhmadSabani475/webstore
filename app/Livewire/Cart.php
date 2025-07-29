<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Actions\ValidateCartStock;
use Illuminate\Support\Collection;
use App\Contract\CartServiceInterface;
use Illuminate\Validation\ValidationException;

class Cart extends Component
{
    public string $sub_total;
    public string $total;
    public function mount(CartServiceInterface $cart)
    {
        $all = $cart->all();
        $this->sub_total = $all->total_formatted;
        $this->total = $this->sub_total;
    }
    #[Computed()]
    public function getItemsProperty(CartServiceInterface $cart): Collection
    {
        return $cart->all()->items->toCollection();

    }
    public function checkout()
    {
        return redirect()->route('checkout');
    }
    public function render()
    {
        return view('livewire.cart', [
            'items' => $this->items
        ]);
    }
}
