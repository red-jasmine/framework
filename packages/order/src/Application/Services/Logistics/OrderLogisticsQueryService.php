<?php

namespace RedJasmine\Order\Application\Services\Logistics;

use RedJasmine\Order\Domain\Repositories\OrderLogisticsReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;


class OrderLogisticsQueryService extends ApplicationQueryService
{

    public function __construct(protected OrderLogisticsReadRepositoryInterface $repository)
    {

    }

}
