<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminGroupResource\Pages;

use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminGroup extends EditRecord
{
    protected static string $resource = AdminGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
