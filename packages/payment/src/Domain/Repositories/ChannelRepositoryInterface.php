<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Channel;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method Channel  find($id)
 */
interface ChannelRepositoryInterface extends RepositoryInterface
{

    public function findByCode(string $code) : ?Channel;

}
