<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderPaymentResource\Pages;

use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderPayments extends ListRecords
{
    protected static string $resource = OrderPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
