<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupResource\Pages;

use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class EditCardGroup extends EditRecord
{
    use ResourcePageHelper;

    protected static string $resource = CardGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
            //Actions\ForceDeleteAction::make(),
            //Actions\RestoreAction::make(),
        ];
    }
}
