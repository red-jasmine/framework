<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources\CardResource\Pages;

use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ListCards extends ListRecords
{
    protected static string $resource = CardResource::class;
    use ResourcePageHelper;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
