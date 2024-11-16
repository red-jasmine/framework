<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class MerchantAppData extends Data
{

    public UserInterface $owner;

    public string        $channel;


}
