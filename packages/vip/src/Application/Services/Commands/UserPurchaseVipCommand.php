<?php

namespace RedJasmine\Vip\Application\Services\Commands;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;

class UserPurchaseVipCommand extends Data
{

    public UserInterface $owner;

    public int $id;

    public int $quantity = 1;


}