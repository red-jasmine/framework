<?php

namespace RedJasmine\Support\Foundation\Service;


use Illuminate\Contracts\Container\BindingResolutionException;
use RedJasmine\Support\Foundation\Hook\HasHooks;
use ReflectionException;

/**
 * 服务基础
 *
 *
 */
abstract class Service
{


    /**
     * 钩子能力
     */
    use HasHooks;

    /**
     * 宏能力
     */
    use ServiceMacroAble;


    /**
     * @param $macro
     * @param $method
     * @param $parameters
     *
     * @return mixed
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    public function callMacro($macro, $method, $parameters) : mixed
    {

        return $this->hook(
            $method,
            count($parameters) === 1 ? $parameters[0] : $parameters,
            fn() => $macro->handle(...$parameters));

    }


}
