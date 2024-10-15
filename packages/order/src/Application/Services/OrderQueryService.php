<?php

namespace RedJasmine\Order\Application\Services;

use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Infrastructure\ReadRepositories\OrderReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;


class OrderQueryService extends ApplicationQueryService
{

    public function __construct(protected OrderReadRepositoryInterface $repository)
    {

    }

    public function allowedIncludes() : array
    {
        return [
            'products',
            'payments',
            'info',
            'products.info',
            'logistics',
            'address'
        ];
    }


}
