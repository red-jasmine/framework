<?php

namespace RedJasmine\Payment\Domain\Transformer;

use RedJasmine\Payment\Domain\Data\SettleReceiverData;
use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Domain\Models\ValueObjects\SettleReceiverAccount;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class SettleReceiverTransformer implements TransformerInterface
{
    /**
     * @param  SettleReceiverData  $data
     * @param  SettleReceiver  $model
     *
     * @return SettleReceiver
     */
    public function transform($data, $model) : SettleReceiver
    {


        $model->system_merchant_app_id = $data->systemMerchantAppId;
        $model->receiver_type          = $data->receiverType;
        $model->receiver_id            = $data->receiverId;
        $model->channel_code           = $data->channelCode;
        $model->channel_merchant_id    = $data->channelMerchantId;
        $model->account                = $data->account;
        $model->account_type           = $data->accountType;
        $model->name                   = $data->name;
        $model->relation_type          = $data->relationType;
        $model->cert_type              = $data->certType ?? null;
        $model->cert_no                = $data->certNo ?? null;


        return $model;
    }


}
