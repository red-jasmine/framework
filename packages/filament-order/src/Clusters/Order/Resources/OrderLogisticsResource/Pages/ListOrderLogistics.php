<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderLogisticsResource\Pages;

use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderLogisticsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderLogistics extends ListRecords
{
    protected static string $resource = OrderLogisticsResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
