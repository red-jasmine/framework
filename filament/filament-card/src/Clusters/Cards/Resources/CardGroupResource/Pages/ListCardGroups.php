<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupResource\Pages;

use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ListCardGroups extends ListRecords
{

    use ResourcePageHelper;
    protected static string $resource = CardGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
