<?php

namespace RedJasmine\Payment\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
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


    /**
     * @param  int  $systemMerchantAppId
     * @param  string  $receiverType
     * @param  string  $receiverId
     * @param  string  $channelCode
     *
     * @return Collection|SettleReceiver[]
     */
    public function findByMerchantAppReceivers(
        int $systemMerchantAppId,
        string $receiverType,
        string $receiverId,
        string $channelCode
    ) : Collection;

}
