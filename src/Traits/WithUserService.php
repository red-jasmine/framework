<?php

namespace RedJasmine\Support\Traits;

use RedJasmine\Support\Contracts\UserInterface;

trait WithUserService
{
    /**
     * 所属人
     * @var UserInterface|null
     */
    private ?UserInterface $owner = null;

    /**
     * 获取调用服务所属人
     * @return UserInterface|null
     */
    public function getOwner() : ?UserInterface
    {
        return $this->owner;
    }

    /**
     * @param UserInterface|null $owner
     *
     * @return static
     */
    public function setOwner(?UserInterface $owner) : static
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * 操作人
     * @var UserInterface|null
     */
    private ?UserInterface $operator = null;

    public function getOperator() : ?UserInterface
    {
        return $this->operator;
    }

    public function setOperator(?UserInterface $operator) : static
    {
        $this->operator = $operator;
        return $this;
    }



}
