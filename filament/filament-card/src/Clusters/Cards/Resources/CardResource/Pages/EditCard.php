<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources\CardResource\Pages;

use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class EditCard extends EditRecord
{
    protected static string $resource = CardResource::class;
    use ResourcePageHelper;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
