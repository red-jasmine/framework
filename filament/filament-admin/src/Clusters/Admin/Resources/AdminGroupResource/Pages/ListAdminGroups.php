<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminGroupResource\Pages;

use Filament\Actions\CreateAction;
use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminGroups extends ListRecords
{
    protected static string $resource = AdminGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
