<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\PaymentChannelApp;
use RedJasmine\Payment\Domain\Models\PaymentChannelProduct;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method PaymentChannelProduct  find($id)
 */
interface ChannelProductRepositoryInterface extends RepositoryInterface
{


    public function findByCode(string $channelCode, string $code) : ?PaymentChannelProduct;

}
