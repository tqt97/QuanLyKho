<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public Product $product;

    #[Url()]
    public string $sort = 'asc';

    public function toggleSort()
    {
        $this->sort = ($this->sort === 'desc') ? 'asc' : 'desc';
    }

    #[Url()]
    public string $search = '';

    // public function updating($key): void
    // {
    //     if ($key === 'search') {
    //         $this->resetPage();
    //     }
    // }

    #[On('search')]
    public function updateSearch($search): void
    {
        $this->search = $search;
        dd($search);
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->sort = 'desc';
        $this->resetPage();
    }

    public function render()
    {
        // $products = Cache::remember('products', 60, function () {
        //     return Product::query()
        //         ->whereLike(['common_title', 'product_title'], $this->search)
        //         ->orderBy('created_at', $this->sort)
        //         ->paginate(6);
        // });

        $products = Product::query()
                ->whereLike(['common_title', 'product_title'], $this->search)
                ->orderBy('created_at', $this->sort)
                ->paginate(6);

        return view(
            'livewire.product.index',
            [
                'products' => $products,
            ]

        );
    }
}
