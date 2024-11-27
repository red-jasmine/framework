<?php

namespace RedJasmine\Payment\Domain\Services;

use RedJasmine\Payment\Domain\Data\PaymentEnvironmentData;
use RedJasmine\Payment\Domain\Models\PaymentMerchantApp;

class PaymentPlatformRouteService
{

    public function getPlatforms(PaymentMerchantApp $merchantApp, PaymentEnvironmentData $paymentEnvironment) : array
    {
        // TODO

        return [];
    }

}
