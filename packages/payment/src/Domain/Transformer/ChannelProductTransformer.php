<?php

namespace RedJasmine\Payment\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\NoReturn;
use RedJasmine\Payment\Domain\Data\ChannelProductData;
use RedJasmine\Payment\Domain\Models\PaymentChannelProduct;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class ChannelProductTransformer implements TransformerInterface
{
    /**
     * @param ChannelProductData|Data $data
     * @param PaymentChannelProduct|Model|null $channelProduct
     * @return PaymentChannelProduct
     */
    public function transform(ChannelProductData|Data $data, PaymentChannelProduct|Model|null $channelProduct = null) : PaymentChannelProduct
    {
        $channelProduct               = $channelProduct ?? PaymentChannelProduct::newModel();
        $channelProduct->channel_code = $data->channelCode;
        $channelProduct->code         = $data->code;
        $channelProduct->name         = $data->name;
        $channelProduct->rate         = $data->rate;
        $channelProduct->status       = $data->status;
        $channelProduct->setModes($data->modes);
        return $channelProduct;

    }
}
