<?php

namespace RedJasmine\Vip\Application\Services\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class UserPurchaseVipCommand extends Data
{

    public UserInterface $owner;

    public int $id;

    public int $quantity = 1;


}