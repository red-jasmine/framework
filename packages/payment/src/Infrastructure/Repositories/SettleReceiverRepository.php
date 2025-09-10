<?php

namespace RedJasmine\Payment\Infrastructure\Repositories;

use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Domain\Repositories\SettleReceiverRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;
class SettleReceiverRepository extends Repository implements SettleReceiverRepositoryInterface
{

    protected static string $modelClass = SettleReceiver::class;

    public function findByMerchantAppReceivers(
        int $merchantAppId,
        string $receiverType,
        int $receiverId,
        string $channelCode

    ) : Collection {
        return $this->query()->where('merchant_app_id', $merchantAppId)
                    ->where('receiver_type', $receiverType)
                    ->where('receiver_id', $receiverId)
                    ->where('channel_code', $channelCode)
                    ->get();
    }


    public function findByMerchantAppReceiver(
        int $systemMerchantAppId,
        string $receiverType,
        string $receiverId,
        string $channelCode,
        string $channelMerchantId = SettleReceiver::ALL_CHANNEL_MERCHANT,
    ) : ?SettleReceiver {
        return $this->query()
                    ->where('system_merchant_app_id', $systemMerchantAppId)
                    ->where('receiver_type', $receiverType)
                    ->where('receiver_id', $receiverId)
                    ->where('channel_code', $channelCode)
                    ->where('channel_merchant_id', $channelMerchantId)
                    ->first();

    }
}

