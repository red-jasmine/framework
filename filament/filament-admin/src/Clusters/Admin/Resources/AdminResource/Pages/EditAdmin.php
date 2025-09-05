<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource\Pages;

use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class EditAdmin extends EditRecord
{
    use ResourcePageHelper;
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
