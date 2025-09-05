<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions\Table;

use Filament\Tables\Actions\Action;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions\RefundRemarks;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions\RefundReshipment;

class RefundSellerRemarksTableAction extends Action
{

    use RefundRemarks;
}
