<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderRefund extends CreateRecord
{
    protected static string $resource = OrderRefundResource::class;
    use ResourcePageHelper;
}
