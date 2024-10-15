<?php

namespace RedJasmine\Order\Application\Services;

use RedJasmine\Order\Infrastructure\ReadRepositories\RefundReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;

class RefundQueryService extends ApplicationQueryService
{
    public function __construct(protected RefundReadRepositoryInterface $repository)
    {

    }


    public function allowedIncludes() : array
    {
        return [
            'logistics',
            'order',
            'orderProduct',
            'payments'
        ];
    }


}
