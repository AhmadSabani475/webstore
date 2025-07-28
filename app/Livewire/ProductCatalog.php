<?php

namespace App\Livewire;

use App\Data\ProductCollectionData;
use App\Models\Tag;
use App\Models\Product;
use Livewire\Component;
use App\Data\ProductData;
use Livewire\WithPagination;

class ProductCatalog extends Component
{
    use WithPagination;

    public $queryString = [
        'select_collection' => ['except' => []],
        'search' => ['except' => ''],
        'sort_by' => ['except' => 'newest']
    ];
    public array $select_collection = [];
    public string $search = '';
    public string $sort_by = 'newest';
    public function mount()
    {
        $this->validate();
    }
    protected function rules()
    {
        return [
            'select_collection' => 'array',
            'select_collection.*' => 'integer|exists:tags,id',
            'search' => 'nullable|min:3|max:30|string',
            'sort_by' => 'in:newest,latest,price_asc,price_dsc'
        ];
    }
    public function applyFilters()
    {
        $this->validate();
        $this->resetPage();
    }
    public function resetFilters()
    {
        $this->select_collection = [];
        $this->search = '';
        $this->sort_by = 'newest';
        $this->resetErrorBag();
        $this->resetPage();
    }
    public function render()
    {
        $collections = ProductCollectionData::collect([]);
        $products = ProductData::collect([]);
        // erly return
        if ($this->getErrorBag()->isNotEmpty()) {
            return view('livewire.product-catalog', compact('collections', 'products'));
        }

        $resultCollect = Tag::query()->withType('collection')->withCount('products')->get();
        // $result = Product::paginate(1); // orm / Database Query
        $query = Product::query();
        if ($this->search) {
            $query->where('name', 'LIKE', "%$this->search%");
        }
        if (!empty($this->select_collection)) {
            $query->whereHas('tags', function ($query) {
                $query->whereIn('id', $this->select_collection);
            });
        }

        switch ($this->sort_by) {
            case 'latest':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_dsc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = ProductData::collect($query->paginate(
            9
        ));
        $collections = ProductCollectionData::collect($resultCollect);
        return view('livewire.product-catalog', compact('products', 'collections'));
    }
}
