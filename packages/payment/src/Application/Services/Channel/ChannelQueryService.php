<?php

namespace RedJasmine\Payment\Application\Services\Channel;

use RedJasmine\Payment\Domain\Repositories\ChannelReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;

class ChannelQueryService extends ApplicationQueryService
{

    public function __construct(protected ChannelReadRepositoryInterface $repository)
    {

    }

}
