<?php

namespace App\Livewire;

use Livewire\Component;
use App\Data\ProductData;
use App\Data\CartItemData;
use App\Contract\CartServiceInterface;

class AddToCart extends Component
{
    public int $quantity;
    public float $price;
    public int $stock;
    public int $weight;
    public string $sku;


    public string $label = 'Add To Cart';
    public function mount(ProductData $product, CartServiceInterface $cart)
    {
        $this->sku = $product->sku;
        $this->stock = $product->stock;
        $this->price = $product->price;
        $this->weight = $product->weight;
        $this->quantity = $cart->getItemBySku($product->sku)->quantity ?? 1;
        $this->validate();
    }
    protected function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1', "max:{$this->stock}"]
        ];
    }
    public function addToCart(CartServiceInterface $cart)
    {
        $this->validate();
        $cart->addOrUpdate(new CartItemData(
            sku: $this->sku,
            quantity: $this->quantity,
            weight: $this->weight,
            price: $this->price
        ));
        session()->flash('success','product add to cart');
        $this->dispatch('cart-updated');
        redirect()->route('cart');
    }
    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
