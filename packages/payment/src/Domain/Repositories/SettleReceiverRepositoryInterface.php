<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method SettleReceiver  find($id)
 */
interface SettleReceiverRepositoryInterface extends RepositoryInterface
{
    public function findByMerchantAppReceivers(int $merchantAppId, string $receiverType, int $receiverId, string $channelCode);
}

