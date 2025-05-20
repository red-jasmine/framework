<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource\Pages;

use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
