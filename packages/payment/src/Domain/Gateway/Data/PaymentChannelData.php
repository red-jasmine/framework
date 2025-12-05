<?php

namespace RedJasmine\Payment\Domain\Gateway\Data;

use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Support\Foundation\Data\Data;

class PaymentChannelData extends Data
{

    public ChannelApp $channelApp;


    public ?ChannelProduct $channelProduct = null;


}
