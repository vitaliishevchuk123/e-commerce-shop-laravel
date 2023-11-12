<div>
    <div class="m-4 text-red-600">asd</div>
    <form wire:submit.prevent="searchCategory" class="mb-4">
        <input type="text" wire:model.defer="search"
               placeholder="Search categories"
               class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white rounded px-4 py-2 focus:outline-none focus:border-blue-500 transition duration-300"/>
        <button class="dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded">
            Search
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
