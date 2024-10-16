<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource\Pages;

use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ListCardGroupBindProducts extends ListRecords
{

    use ResourcePageHelper;
    protected static string $resource = CardGroupBindProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
