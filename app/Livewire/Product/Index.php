<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
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

    // #[Computed()]
    // public function products()
    // {
    //     return Product::query()
    //         // ->with('categories')
    //         // ->when($this->search, fn($query, $search) => $query->where('common_title', 'like', '%' . $search . '%'))
    //         // ->when($this->search, fn($query, $search) => $query->orWhere('product_title', 'like', '%' . $search . '%'))
    //         // ->when($this->search, fn($query, $search) => $query->orWhere('description', 'like', '%' . $search . '%'))
    //         // ->search($this->search)
    //         ->when($this->search, fn($query, $search) => $query->search($search))
    //         // ->when($this->sort, fn($query, $sort) => $query->orderBy('created_at', $sort))
    //         ->orderBy('created_at', $this->sort)
    //         ->paginate(10);
    // }
    public function render()
    {
        // Post::whereLike(['name', 'text', 'author.name', 'tags.name'], $searchTerm)->get();

        $products = Product::query()
            // ->when($this->search, fn($query, $search) => $query->where('common_title', 'like', '%' . $search . '%'))
            // ->when($this->search, fn($query, $search) => $query->where('product_title', 'like', '%' . $search . '%'))
            // ->when($this->search, fn(Builder $query) => $query->search($this->search))
            ->whereLike(['common_title', 'product_title'], $this->search)
            ->orderBy('created_at', $this->sort)
            ->paginate(6);

        // dd($products);
        return view(
            'livewire.product.index',
            [
                'products' => $products,
            ]

        );
    }
}
