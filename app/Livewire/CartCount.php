<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Contract\CartServiceInterface;

class CartCount extends Component
{
    public int $count;
    public function mount(CartServiceInterface $cart)
    {
        $this->count = $cart->all()->total_quantity;
    }

    #[On('cart-updated')]
    public function updateCount(CartServiceInterface $cart)
    {
        $this->count = $cart->all()->total_quantity;
    }
    public function render()
    {
        return view('livewire.cart-count');
    }
}
