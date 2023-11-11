<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Component;

class CategoryTree extends Component
{
    public Collection $categories;
    public string $search;
    public Category $foundCategory;

    public function render()
    {
        $this->categories = Category::with('children')->root()->get();

        return view('livewire.category-tree');
    }

    public function searchCategory()
    {
        $this->foundCategory = Category::where('name', 'like', '%' . $this->search . '%')->first();
        if ($this->foundCategory) {
            $parentIds = $this->foundCategory->parents(0)->pluck('id')->toArray();
            $this->dispatch('opedSearchedCategory', $parentIds, $this->foundCategory->id);
        }
    }
}
