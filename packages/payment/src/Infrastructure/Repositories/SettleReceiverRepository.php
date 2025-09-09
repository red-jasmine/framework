<?php

namespace RedJasmine\Payment\Infrastructure\Repositories;

use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Domain\Repositories\SettleReceiverRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class SettleReceiverRepository extends Repository implements SettleReceiverRepositoryInterface
{

    protected static string $modelClass = SettleReceiver::class;

    public function findByMerchantAppReceivers(int $merchantAppId, string $receiverType, int $receiverId, string $channelCode)
    {
        return static::$modelClass::where('merchant_app_id', $merchantAppId)
            ->where('receiver_type', $receiverType)
            ->where('receiver_id', $receiverId)
            ->where('channel_code', $channelCode)
            ->get();
    }

}

