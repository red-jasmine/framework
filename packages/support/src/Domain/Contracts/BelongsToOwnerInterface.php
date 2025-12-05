<?php

namespace RedJasmine\Support\Domain\Contracts;

interface BelongsToOwnerInterface
{

    /**
     * 所属老板
     * @return UserInterface
     */
    public function owner() : UserInterface;

}
