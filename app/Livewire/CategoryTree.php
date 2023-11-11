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
    public Collection $foundCategories;

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
        $this->foundCategories = Category::query()
            ->where(DB::raw('lower(name)'), 'like', '%' . strtolower($this->search) . '%')
            ->get();
        $parentIds = [];
        $foundedCatIds = [];
        foreach ($this->foundCategories as $cat) {
            $parentIds = array_merge($parentIds, $cat->parents(0)->pluck('id')->toArray());
            $foundedCatIds[] = $cat->id;
        }
        $this->dispatch('opedSearchedCategory', $parentIds, $foundedCatIds);
    }
}
