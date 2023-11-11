<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategoryItem extends Component
{
    public Category $category;
    public $isOpen = false;
    public $isRoot = false;
    public $foundedCatIds = [];
    public array $searchParentIds = [];

    protected $listeners = ['opedSearchedCategory', 'styleFounded'];

    public function mount(Category $category)
    {
        $this->category = $category;
    }

    public function render()
    {
        return view('livewire.category-item');
    }

    public function resetSearch()
    {
        $this->foundedCatIds = [];
        $this->searchParentIds = [];
    }

    public function toggleCategory($categoryId)
    {
        $this->resetSearch();
        if ($this->category->id == $categoryId) {
            $this->isOpen = !$this->isOpen;
        }
    }

    public function opedSearchedCategory(array $searchParentIds, array $foundedCatIds)
    {
        $this->foundedCatIds = $foundedCatIds;
        $this->searchParentIds = $searchParentIds;
    }
}

