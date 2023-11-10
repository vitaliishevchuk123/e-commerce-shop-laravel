<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Component;

class TreeView extends Component
{
    public $treeData;
    public bool $isChild = false;
    public string $search = '';

    public function mount($treeData = null)
    {
        $this->treeData = $treeData ?? $this->mapData(
            Category::query()
                ->root()
                ->withCount('children')
                ->get()
        );
    }

    public function getChildren(int $nodeId)
    {
        $this->treeData[$nodeId]['open'] = !$this->treeData[$nodeId]['open'];
        if (!count($this->treeData[$nodeId]['children'])) {
            $this->treeData[$nodeId]['children'] = $this->mapData(
                Category::query()
                    ->where('parent_id', $nodeId)
                    ->withCount('children')
                    ->get()
            );
        }
    }

    public function render()
    {
        return view('livewire.tree-view');
    }

    private function mapData(Collection $collection): array
    {
        return $collection->mapWithKeys(function (Category $category) {
            return [
                $category->id => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'open' => false,
                    'children_count' => $category->children_count,
                    'children' => [],
                ]
            ];
        })->toArray();
    }
}
