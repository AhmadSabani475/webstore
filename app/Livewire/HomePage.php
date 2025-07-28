<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Data\ProductData;

class HomePage extends Component
{
    public function render()
    {
        $feature_product = ProductData::collect(
            Product::query()->inRandomOrder()->limit(3)->get()
        );
        $latest_product = ProductData::collect(
            Product::query()->latest()->limit(3)->get()
        );
        return view('livewire.home-page', compact('feature_product', 'latest_product'));
    }
}
