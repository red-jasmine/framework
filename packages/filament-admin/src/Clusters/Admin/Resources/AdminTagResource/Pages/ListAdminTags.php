<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminTagResource\Pages;

use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ListAdminTags extends ListRecords
{
    use ResourcePageHelper;
    protected static string $resource = AdminTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
