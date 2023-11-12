<?php

namespace App\Filament\Resources\CategoryResource\Widgets;

use Filament\Widgets\Widget;

class CategoryTree extends Widget
{
    protected int | string | array $columnSpan = 'full';
    protected static string $view = 'filament.resources.category-resource.widgets.category-tree';

}
