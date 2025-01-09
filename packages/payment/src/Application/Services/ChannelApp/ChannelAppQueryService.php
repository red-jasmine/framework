<?php

namespace RedJasmine\Payment\Application\Services\ChannelApp;

use RedJasmine\Payment\Domain\Repositories\ChannelAppReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;

class ChannelAppQueryService extends ApplicationQueryService
{

    public function __construct(protected ChannelAppReadRepositoryInterface $repository)
    {

    }

}
