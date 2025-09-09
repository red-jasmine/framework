<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method ChannelProduct  find($id)
 */
interface ChannelProductRepositoryInterface extends RepositoryInterface
{


    public function findByCode(string $channelCode, string $code) : ?ChannelProduct;

}

