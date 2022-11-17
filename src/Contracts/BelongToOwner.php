<?php

namespace RedJasmine\Support\Contracts;

interface BelongToOwner
{

    /**
     * 所属人
     * @return User
     */
    public function getOwner() : User;

}
