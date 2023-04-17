<?php

namespace RedJasmine\Support\Services;

use RedJasmine\Support\Contracts\UserInterface;

trait WithOwner
{

    /**
     * 所属者
     * @var UserInterface
     */
    public UserInterface $owner;

    /**
     * @return UserInterface
     */
    public function getOwner() : UserInterface
    {
        return $this->owner;
    }

    /**
     * @param UserInterface $owner
     * @return self
     */
    public function setOwner(UserInterface $owner) : self
    {
        $this->owner = $owner;
        return $this;
    }






}
