<?php

namespace RedJasmine\Payment\Application\Commands\Merchant;

use RedJasmine\Payment\Domain\Data\MerchantData;

class MerchantUpdateCommand extends MerchantData
{
    public int $id;
}
