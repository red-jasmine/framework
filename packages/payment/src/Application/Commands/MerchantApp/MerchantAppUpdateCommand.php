<?php

namespace RedJasmine\Payment\Application\Commands\MerchantApp;

use RedJasmine\Payment\Domain\Data\MerchantAppData;

class MerchantAppUpdateCommand extends MerchantAppData
{
    public int $id;
}
