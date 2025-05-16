<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagCategoryResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserTagCategory extends EditRecord
{
    use ResourcePageHelper;
    protected static string $resource = UserTagCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
