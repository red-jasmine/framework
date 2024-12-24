<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Domain\Repositories\ChannelProductReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;

class ChannelProductQueryService extends ApplicationQueryService
{

    public function __construct(public ChannelProductReadRepositoryInterface $repository)
    {

    }

}
