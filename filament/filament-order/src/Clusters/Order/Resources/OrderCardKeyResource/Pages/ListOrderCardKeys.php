<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderCardKeyResource\Pages;

use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderCardKeyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderCardKeys extends ListRecords
{
    protected static string $resource = OrderCardKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
