<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources\CardResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
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
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
