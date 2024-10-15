<?php

namespace RedJasmine\Support\Contracts;

interface BelongsToOwnerInterface
{

    /**
     * 所属老板
     * @return UserInterface
     */
    public function owner() : UserInterface;

}
