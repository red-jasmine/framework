<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminTagResource\Pages;

use Filament\Actions\DeleteAction;
use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class EditAdminTag extends EditRecord
{
    use ResourcePageHelper;
    protected static string $resource = AdminTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
