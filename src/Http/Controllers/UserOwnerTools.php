<?php

namespace RedJasmine\Support\Http\Controllers;


use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Contracts\UserInterface;

trait UserOwnerTools
{
    /**
     * 当前所属人
     * @return UserInterface|null
     */
    public function getOwner() : ?UserInterface
    {
        if ($this->getUser() instanceof BelongsToOwnerInterface) {
            request()->user()->owner();
        }

        return $this->getUser();
    }

    /**
     * 当前所属人
     * @return null|User
     */
    public function getUser() : ?UserInterface
    {
        return request()->user();
    }


}
