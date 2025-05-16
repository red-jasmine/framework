<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserGroupResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserGroup extends EditRecord
{
    use ResourcePageHelper;
    protected static string $resource = UserGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
