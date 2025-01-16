<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface SettleReceiverReadRepositoryInterface extends ReadRepositoryInterface
{

    public function findByMerchantAppReceiver(
        int $systemMerchantAppId,
        string $receiverType,
        string $receiverId,
        string $channelCode,
        string $channelMerchantId = SettleReceiver::ALL_CHANNEL_MERCHANT,
    ) : ?SettleReceiver;

}
