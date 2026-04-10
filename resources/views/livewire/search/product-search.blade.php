<?php

use Livewire\Component;
use App\Infrastructure\Persistence\Models\Product;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;
    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function with()
    {
        return [
            'items' => Product::searchWithName($this->search)
                ->latest()->paginate(10)
        ];
    }
};
?>

<div>
    <x-ui.search model="search" />
    <x-ui.product.product-list :items="$items" />
</div>