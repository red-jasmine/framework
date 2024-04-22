<?php

namespace RedJasmine\Support\Application;

use Closure;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class Command extends Data
{


    protected UserInterface|Closure|null $operator = null;

    public function setOperator(UserInterface|Closure|null $operator = null) : static
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @return UserInterface|null
     */
    public function getOperator() : ?UserInterface
    {
        $operator = $this->operator;
        if (is_callable($operator)) {
            return $operator();
        }
        return $operator;
    }


}
