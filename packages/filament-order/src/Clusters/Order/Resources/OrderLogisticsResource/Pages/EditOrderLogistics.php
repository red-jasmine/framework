<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderLogisticsResource\Pages;

use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderLogisticsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderLogistics extends EditRecord
{
    protected static string $resource = OrderLogisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
