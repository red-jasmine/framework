<?php

namespace RedJasmine\Support\Application;

use RedJasmine\Support\Foundation\HasServiceContext;
use RedJasmine\Support\Foundation\Service\BootTrait;

abstract class Service
{

    use BootTrait;

    use ServiceMacro;

    // 有服务上下文的
    use HasServiceContext;

    /**
     * @template T
     * @param T $macro
     *
     * @return T
     */
    public function makeMacro(mixed $macro)
    {
        if (is_string($macro)) {
            $macro = app($macro);
            if (method_exists($macro, 'setOperator')) {
                $macro->setOperator($this->getOperator());
            }
            return $macro;
        }
        return $macro;

    }


}
