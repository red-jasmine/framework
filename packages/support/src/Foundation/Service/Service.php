<?php

namespace RedJasmine\Support\Foundation\Service;


use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\ResolvesRouteDependencies;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use RedJasmine\Support\Foundation\Hook\HasHooks;
use ReflectionException;
use Illuminate\Pipeline\Pipeline;

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
     * @template T
     * @param  T  $macro
     * @param   $method
     * @param   $parameters
     *
     * @return T
     */
    public function makeMacro(mixed $macro, $method, $parameters) : mixed
    {

        if (is_string($macro)) {
            $macro = app($macro, ['service' => $this]);
        }

        if ($macro instanceof MacroAwareService) {
            $macro->setService($this);
        }
        if ($macro instanceof MacroAwareArguments) {
            $macro->setArguments($parameters);
        }

        return $macro;
    }

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
