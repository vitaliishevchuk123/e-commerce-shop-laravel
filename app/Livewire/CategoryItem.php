<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategoryItem extends Component
{
    public Category $category;
    public $isOpen = false;
    public $isRoot = false;
    public $foundedId = null;
    public array $searchParentIds = [];

    protected $listeners = ['opedSearchedCategory', 'styleFounded'];

    public function render()
    {
        return view('livewire.category-item');
    }

    public function resetSearch()
    {
        $this->foundedId = null;
        $this->searchParentIds = [];
    }

    public function toggleCategory($categoryId)
    {
        $this->resetSearch();
        if ($this->category->id == $categoryId) {
            $this->isOpen = !$this->isOpen;
        }
    }

    public function opedSearchedCategory(array $searchParentIds, int $foundedId)
    {
        $this->foundedId = $foundedId;
        $this->searchParentIds = $searchParentIds;
    }
}

