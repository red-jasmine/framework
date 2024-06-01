<?php

namespace RedJasmine\Support\Foundation\Service;

use Closure;
use RedJasmine\Support\Contracts\UserInterface;

trait HasServiceContext
{

    /**
     * 操作人
     * @var UserInterface|Closure|null
     */
    protected UserInterface|Closure|null $operator = null;

    public function getOperator() : UserInterface|Closure|null
    {
        $operator = $this->operator;
        if (is_callable($operator)) {
            return $operator();
        }
        return $operator;
    }

    public function setOperator(UserInterface|Closure|null $operator) : static
    {
        $this->operator = $operator;
        return $this;
    }


}
