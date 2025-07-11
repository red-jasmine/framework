<?php

namespace RedJasmine\Coupon\Application\Services\Coupon\Commands;

use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Support\Application\Commands\CreateCommandHandler;
use RedJasmine\Support\Application\HandleContext;

/**
 * @property-read  CouponApplicationService $service
 */
class CouponCreateCommandHandler extends CreateCommandHandler
{


    protected function validate(HandleContext $context) : void
    {

    }
}