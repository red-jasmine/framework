<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrderRefund extends ViewRecord
{
    protected static string $resource = OrderRefundResource::class;
    use ResourcePageHelper;
    protected function getHeaderActions(): array
    {
        return [
//            Actions\EditAction::make(),
        ];
    }
}
