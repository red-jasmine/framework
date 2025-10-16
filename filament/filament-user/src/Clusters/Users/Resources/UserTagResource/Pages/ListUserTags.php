<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource\Pages;

use Filament\Actions\CreateAction;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserTags extends ListRecords
{
    use ResourcePageHelper;
    protected static string $resource = UserTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
