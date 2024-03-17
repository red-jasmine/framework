<?php

namespace RedJasmine\Support\Foundation\Service;

use RedJasmine\Support\Contracts\UserInterface;

class ServiceManage
{
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
