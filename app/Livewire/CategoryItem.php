<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Computed;

class CategoryItem extends Component
{
    public Category $category;
    public $isRoot = false;
    public array $foundedCatIds = [];
    public array $searchParentIds = [];

    protected $listeners = ['opedSearchedCategory', 'styleFounded'];

    public function render()
    {
        return view('livewire.category-item');
    }

    #[Computed]
    public function isOpen(): bool
    {
        return in_array($this->category->id, $this->searchParentIds);
    }

    #[Computed]
    public function isFounded(): bool
    {
        return in_array($this->category->id, $this->foundedCatIds);
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

