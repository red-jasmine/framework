<?php

namespace RedJasmine\Payment\Application\Services\Merchant\Commands;

use RedJasmine\Payment\Domain\Data\MerchantData;

class MerchantUpdateCommand extends MerchantData
{
    public int $id;
}
