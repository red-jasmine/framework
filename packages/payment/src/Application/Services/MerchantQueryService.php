<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Domain\Repositories\MerchantReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;

class MerchantQueryService extends ApplicationQueryService
{

    public function __construct(protected MerchantReadRepositoryInterface $repository)
    {

    }

}
