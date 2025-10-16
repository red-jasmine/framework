<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions\InfoList;

use Filament\Actions\Action;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions\RefundAgree;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions\RefundReject;

class RefundRejectInfoListAction extends Action
{
    use RefundReject;
}
