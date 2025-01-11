<?php

namespace RedJasmine\Payment\Application\Services\Transfer\Commands;

use RedJasmine\Payment\Domain\Data\ChannelTransferData;
use RedJasmine\Payment\Domain\Models\Enums\TransferStatusEnum;

class TransferFailCommand extends ChannelTransferData
{

    public string $transferNo;

    public TransferStatusEnum $status = TransferStatusEnum::FAIL;

}
