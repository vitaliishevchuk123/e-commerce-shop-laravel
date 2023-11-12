<div>
    <form wire:submit.prevent="searchCategory" class="mb-4 flex align-center gap-2">
        <input type="text" wire:model.defer="search"
               placeholder="Search categories"
               class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white rounded px-4 py-2 focus:outline-none focus:border-blue-500 transition duration-300"/>
        <button class="dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded">
            <x-heroicon-o-magnifying-glass class="h-6" />
        </button>
    </form>
    <ul>
        @foreach($categories as $category)
            <livewire:category-item
                :key="$category->id"
                :is-root="true"
                :category="$category"
                :search-parent-ids="$searchParentIds"
                :founded-cat-ids="$foundedCatIds"
            />
        @endforeach
    </ul>
</div>
