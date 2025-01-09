<?php

namespace RedJasmine\Payment\Application\Services\MerchantApp\Commands;

use RedJasmine\Payment\Domain\Data\MerchantAppData;

class MerchantAppUpdateCommand extends MerchantAppData
{
    public int $id;
}
