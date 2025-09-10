<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method SettleReceiver  find($id)
 */
interface SettleReceiverRepositoryInterface extends RepositoryInterface
{
    public function findByMerchantAppReceivers(
        int $merchantAppId,
        string $receiverType,
        int $receiverId,
        string $channelCode
    ) : Collection;


    public function findByMerchantAppReceiver(
        int $systemMerchantAppId,
        string $receiverType,
        string $receiverId,
        string $channelCode,
        string $channelMerchantId = SettleReceiver::ALL_CHANNEL_MERCHANT,
    ) : ?SettleReceiver;

}

