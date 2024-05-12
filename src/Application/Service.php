<?php

namespace RedJasmine\Support\Application;

use RedJasmine\Support\Foundation\HasServiceContext;
use RedJasmine\Support\Foundation\Service\BootTrait;

abstract class Service
{

    use BootTrait;

    use ServiceMacro;

    use HasServiceContext;


    /**
     * @template T
     * @param T $macro
     *
     * @return T
     */
    public function makeMacro(mixed $macro) : mixed
    {
        if (is_string($macro)) {
            $macro = app($macro);

            // 设置为当前服务
            if (method_exists($macro, 'setService')) {
                $macro->setService($this);
            }
            if (method_exists($macro, 'setOperator')) {
                $macro->setOperator($this->getOperator());
            }
            return $macro;
        }
        return $macro;

    }
}
