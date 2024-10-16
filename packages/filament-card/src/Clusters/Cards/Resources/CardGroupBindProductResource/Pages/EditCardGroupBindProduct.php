<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource\Pages;

use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class EditCardGroupBindProduct extends EditRecord
{
    protected static string $resource = CardGroupBindProductResource::class;
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
