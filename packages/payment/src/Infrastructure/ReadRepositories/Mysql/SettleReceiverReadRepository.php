<?php

namespace RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Domain\Repositories\ChannelAppReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\SettleReceiverReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class SettleReceiverReadRepository extends QueryBuilderReadRepository implements SettleReceiverReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = SettleReceiver::class;

    public function findByMerchantAppReceiver(
        int $systemMerchantAppId,
        string $receiverType,
        string $receiverId,
        string $channelCode,
        string $channelMerchantId = SettleReceiver::ALL_CHANNEL_MERCHANT,
    ) : ?SettleReceiver {

        return $this->query(null)
                    ->where('system_merchant_app_id', $systemMerchantAppId)
                    ->where('receiver_type', $receiverType)
                    ->where('receiver_id', $receiverId)
                    ->where('channel_code', $channelCode)
                    ->where('channel_merchant_id', $channelMerchantId)
                    ->first();
    }


}
