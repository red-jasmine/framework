<?php

namespace RedJasmine\Product\UI\Http\Owner\Api\Controllers;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;

class Controller extends \RedJasmine\Support\Http\Controllers\Controller
{


    public function getOwner() : ?UserInterface
    {
        return UserData::from([ 'type' => 'seller', 'id' => 1 ]);
    }

    public function getUser(): ?UserInterface
    {
        return UserData::from([ 'type' => 'seller', 'id' => 1 ]);
    }
}
