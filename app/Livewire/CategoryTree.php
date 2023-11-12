<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CategoryTree extends Component
{
    public Collection $categories;
    public string $search = '';
    public array $searchParentIds = [];
    public array $foundedCatIds = [];

    public function render()
    {
        $this->categories = Category::with('children')->root()->get();

        return view('livewire.category-tree');
    }

    public function searchCategory()
    {
        if (!$this->search) {
            return;
        }
        $foundCategories = Category::query()
            ->where(DB::raw('lower(name)'), 'like', '%' . strtolower($this->search) . '%')
            ->get();
        $this->searchParentIds = [];
        $this->foundedCatIds = [];
        foreach ($foundCategories as $cat) {
            $this->searchParentIds = array_values(array_unique(array_merge($this->searchParentIds, $cat->parents(0)->pluck('id')->toArray())));
            $this->foundedCatIds[] = $cat->id;
        }
        $this->dispatch('opedSearchedCategory', $this->searchParentIds, $this->foundedCatIds);
    }
}
