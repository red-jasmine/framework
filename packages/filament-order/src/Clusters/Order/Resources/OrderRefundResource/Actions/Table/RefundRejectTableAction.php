<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions\Table;

use Filament\Tables\Actions\Action;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions\RefundAgree;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions\RefundReject;

class RefundRejectTableAction extends Action
{

    use RefundReject;

}
