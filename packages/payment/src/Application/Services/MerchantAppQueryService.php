<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Domain\Repositories\MerchantAppReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;

class MerchantAppQueryService extends ApplicationQueryService
{

    public function __construct(protected MerchantAppReadRepositoryInterface $repository)
    {

    }

}
