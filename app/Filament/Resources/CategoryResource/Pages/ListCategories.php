<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
            Action::make('tree')
                ->url(fn (): string => 'categories/tree')
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            //
        ];
    }
}
