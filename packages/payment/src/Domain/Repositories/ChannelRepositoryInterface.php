<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\PaymentChannel;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method PaymentChannel  find($id)
 */
interface ChannelRepositoryInterface extends RepositoryInterface
{

    public function findByCode(string $code) : ?PaymentChannel;

}
