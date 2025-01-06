<?php

namespace RedJasmine\Payment\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\NoReturn;
use RedJasmine\Payment\Domain\Data\ChannelProductData;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class ChannelProductTransformer implements TransformerInterface
{
    /**
     * @param  ChannelProductData|Data  $data
     * @param  ChannelProduct|Model|null  $channelProduct
     *
     * @return ChannelProduct
     */
    public function transform(
        ChannelProductData|Data $data,
        ChannelProduct|Model|null $channelProduct = null
    ) : ChannelProduct {
        $channelProduct               = $channelProduct ?? ChannelProduct::make();
        $channelProduct->type         = $data->type;
        $channelProduct->channel_code = $data->channelCode;
        $channelProduct->code         = $data->code;
        $channelProduct->name         = $data->name;

        $channelProduct->status = $data->status;
        $channelProduct->setModes($data->modes);
        return $channelProduct;

    }
}
