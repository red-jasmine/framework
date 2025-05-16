<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagCategoryResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserTagCategories extends ListRecords
{
    use ResourcePageHelper;
    protected static string $resource = UserTagCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
