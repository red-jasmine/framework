<?php

namespace RedJasmine\Payment\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Payment\Domain\Data\ChannelAppData;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class ChannelAppTransformer implements TransformerInterface
{
    public function transform(Data|ChannelAppData $data, Model|ChannelApp|null $model = null) : ?ChannelApp
    {
        $model = $model ?? ChannelApp::newModel();

        $model->channel_code            = $data->channelCode;
        $model->channel_merchant_id     = $data->channelMerchantId;
        $model->channel_app_id          = $data->channelAppId;
        $model->fee_rate                = $data->feeRate;
        $model->channel_public_key      = $data->channelPublicKey;
        $model->channel_app_public_key  = $data->channelAppPublicKey;
        $model->channel_app_private_key = $data->channelAppPrivateKey;
        $model->status                  = $data->status;
        $model->remarks                 = $data->remarks;
        $model->app_name                = $data->appName;
        $model->merchant_name           = $data->merchantName;
        $model->setRelation('products', collect($data->products));

        return $model;
    }


}
