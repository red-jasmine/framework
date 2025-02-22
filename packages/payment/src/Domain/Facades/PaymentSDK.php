<?php

namespace RedJasmine\Payment\Domain\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\Payment\Domain\Services\PaymentSdkService;

/**
 * @see PaymentSdkService
 */
class PaymentSDK extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return PaymentSdkService::class;
    }
}
