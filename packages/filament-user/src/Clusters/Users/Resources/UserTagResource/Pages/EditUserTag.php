<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserTag extends EditRecord
{
    use ResourcePageHelper;
    protected static string $resource = UserTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
