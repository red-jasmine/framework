<?php

namespace RedJasmine\Support\Http\Controllers;

use RedJasmine\Support\Services\Contracts\User;

trait UserOwnerTools
{
    /**
     * 当前所属人
     * @return null|User
     */
    public function getOwner() : ?User
    {
        return request()->user()->owner() ?? request()->user();
    }

    /**
     * 当前所属人
     * @return null|User
     */
    public function getUser() : ?User
    {
        return request()->user();
    }


}
