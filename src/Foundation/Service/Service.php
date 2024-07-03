<?php

namespace RedJasmine\Support\Foundation\Service;


use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\ResolvesRouteDependencies;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use ReflectionException;
use \Illuminate\Pipeline\Pipeline;

abstract class Service
{

    // halt TODO

    use ResolvesRouteDependencies;


    use BootTrait;

    use ServiceMacroAble;


    protected Container $container;

    protected string $macroMethod = 'handle';


    /**
     * @template T
     * @param T $macro
     * @param   $method
     * @param   $parameters
     *
     * @return T
     * @throws BindingResolutionException
     */
    public function makeMacro(mixed $macro, $method, $parameters) : mixed
    {
        $this->container = app();

        if (is_string($macro)) {
            $macro = $this->container->make($macro);
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
        return app(Pipeline::class)
            ->send($macro)
            ->pipe($this->resolveMacroMiddleware($macro, $method))
            ->then(
                fn() => $macro->handle(
                    ...array_values($this->resolveClassMethodDependencies($parameters, $macro, $this->macroMethod))
                )
            );
    }

    /**
     * 扩展管道配置
     * @return array
     */
    protected function pipelines() : array
    {
        return [];
    }

    public function resolveMacroMiddleware($macro, $method) : array
    {

        // 全局中间件
        $pipelines = static::getGlobalPipelines();

        $macroMiddleware = [];

        if (method_exists($method, 'pipelines')) {
            $macroMiddleware = $method->pipelines();
        }

        $serviceMiddleware = $this->pipelines()[$method] ?? [];

        return array_merge($pipelines, $macroMiddleware, $serviceMiddleware, $this->getConfigPipelines($macro, $method));

    }

    protected ?string $pipelinesConfigKey = null;


    protected function getConfigPipelines($macro, $method) : array
    {

        if ($this->pipelinesConfigKey !== null) {
            return (array)Config::get(Str::finish($this->pipelinesConfigKey, '.') . Str::snake($method), []);
        }
        return [];
    }


    /**
     * 静态管道
     * @var array
     */
    protected static array $globalPipelines = [];

    public static function extendPipelines($pipelines) : void
    {
        static::$globalPipelines[static::class][] = $pipelines;
    }

    public static function getGlobalPipelines() : array
    {
        return static::$globalPipelines[static::class] ?? [];
    }

    public static function setGlobalPipelines(array $globalPipelines) : void
    {
        static::$globalPipelines[static::class] = $globalPipelines;
    }


}
