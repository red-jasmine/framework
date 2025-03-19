<?php

namespace RedJasmine\Support\Application;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Support\Foundation\Hook\HasHooks;

/**
 * @property RepositoryInterface $repository
 * @property ReadRepositoryInterface $readRepository
 */
class ApplicationService
{

    use HasHooks;

    use Macroable {
        __call as macroCall;
        __callStatic as macroCallStatic;
    }


    public function __call($method, $parameters)
    {

        if (! static::hasMacro($method) && static::hasHandler($method)) {
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist.', static::class, $method
            ));
        }

        $macro = static::$macros[$method]??null;

        if($macro === null){
            // 处理器处理流程


        }

        if ($macro instanceof Closure) {
            $macro = $macro->bindTo($this, static::class);
        }

        return $macro(...$parameters);

        try {
            static::macroCall($method, $parameters);
        } catch (\BadMethodCallException $badMethodCallException) {
            if (static::hasHandler($method)) {
                $handler = $this->makeHandler($method);
            }
        }

    }

    public static function hasHandler($name) : bool
    {
        return isset(static::$handlers[$name]);
    }

    public function makeHandler($name)
    {
        $handler = static::$handlers[$name];

        if (is_string($handler) && class_exists($handler)) {
            app($handler, ['service' => $this]);
        }

        return $handler;
    }


    /**
     * @var string
     */
    protected static string $modelClass = Model::class;


    protected static array $handlers = [];

    public static function getHandlers() : array
    {
        return static::$handlers;
    }

    public static function setHandlers(array $handlers) : void
    {
        static::$handlers = $handlers;
    }


    protected static array $commands = [];


    public static function setCommand($command, $handler) : void
    {
        static::$commands[$command] = $handler;
    }


    protected static array $queries = [];


    public static function setQuery($query, $handler) : void
    {
        static::$queries[$query] = $handler;
    }


    public function model() : string
    {
        return static::$modelClass;
    }

}