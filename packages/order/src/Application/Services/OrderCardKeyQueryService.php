<?php

namespace RedJasmine\Order\Application\Services;

use RedJasmine\Order\Domain\Repositories\OrderCardKeyReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;

class OrderCardKeyQueryService extends ApplicationQueryService

{
    public function __construct(protected OrderCardKeyReadRepositoryInterface $repository)
    {

    }
}
