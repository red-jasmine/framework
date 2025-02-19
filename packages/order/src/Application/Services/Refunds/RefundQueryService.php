<?php

namespace RedJasmine\Order\Application\Services\Refunds;

use RedJasmine\Order\Domain\Repositories\RefundReadRepositoryInterface;
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
