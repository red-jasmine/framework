<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
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
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
