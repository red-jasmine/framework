<?php

namespace RedJasmine\Payment\Domain\Factories;

use RedJasmine\Payment\Domain\Data\TransferCreateData;
use RedJasmine\Payment\Domain\Events\Transfers\TransferProcessingEvent;
use RedJasmine\Payment\Domain\Models\Enums\TransferStatusEnum;
use RedJasmine\Payment\Domain\Models\Transfer;

/**
 * TransferFactory 类用于生成转账对象
 */
class TransferFactory
{

    public function create(TransferCreateData $command) : Transfer
    {
        $transfer                  = Transfer::make();
        $transfer->merchant_app_id = $command->merchantAppId;
        $transfer->payee           = $command->payee;
        $transfer->amount          = $command->amount;

        $transfer->transfer_status = TransferStatusEnum::PRE;
        return $transfer;
    }

}
