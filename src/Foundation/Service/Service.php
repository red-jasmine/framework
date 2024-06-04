<?php

namespace RedJasmine\Support\Foundation\Service;

abstract class Service
{

    use BootTrait;

    use ServiceMacro;


    /**
     * @template T
     * @param T $macro
     *
     * @return T
     */
    public function makeMacro(mixed $macro, $method, $parameters) : mixed
    {
        if (is_string($macro)) {
            $macro = app($macro);
            // 设置为当前服务
            if ($macro instanceof MacroAwareService) {
                $macro->setService($this);
            }
            return $macro;
        }
        return $macro;

    }
}
