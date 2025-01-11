<?php

namespace RedJasmine\Payment\Application\Services\Transfer\Commands;

use RedJasmine\Payment\Domain\Data\ChannelTransferData;
use RedJasmine\Payment\Domain\Models\Enums\TransferStatusEnum;

/**
 * 转账成功
 */
class TransferSuccessCommand extends ChannelTransferData
{

    public string $transferNo;

    public TransferStatusEnum $status = TransferStatusEnum::SUCCESS;

}
