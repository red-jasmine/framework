<?php

namespace RedJasmine\Support\Application;


use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;
use RedJasmine\Support\Application\Commands\CreateCommandHandler;
use RedJasmine\Support\Application\Commands\DeleteCommandHandler;
use RedJasmine\Support\Application\Commands\UpdateCommandHandler;
use RedJasmine\Support\Application\QueryHandlers\FindQueryHandler;
use RedJasmine\Support\Application\QueryHandlers\PaginateQueryHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Support\Foundation\Hook\HasHooks;
use ReflectionClass;

/**
 * @method Model crate(Data $command)
 * @method Model update(Data $command)
 * @method bool delete(Data $command)
 * @method Model find(FindQuery $query)
 * @method paginate(PaginateQuery $query)
 * @property RepositoryInterface $repository
 * @property ReadRepositoryInterface $readRepository
 */
class ApplicationService
{

    use HasHooks;

    use Macroable {
        __call as macroCall;
        Macroable::hasMacro as macroHasMacro;
        __callStatic as macroCallStatic;
    }


    protected static array $handlers = [
        'create'   => CreateCommandHandler::class,
        'update'   => UpdateCommandHandler::class,
        'delete'   => DeleteCommandHandler::class,
        'find'     => FindQueryHandler::class,
        'paginate' => PaginateQueryHandler::class
    ];

    public static function getMacros() : array
    {
        return array_merge(static::$handlers, static::$macros);
    }


    public function __call($method, $parameters)
    {

        if (!isset(static::getMacros()[$method])) {
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist.', static::class, $method
            ));
        }

        $macro = static::getMacros()[$method];


        if ($macro instanceof Closure) {
            $macro = $macro->bindTo($this, static::class);
        }
        $macro = $this->makeHandler($macro);
        if (method_exists($macro, 'handle')) {
            return $macro->handle(...$parameters);
        }
        return $macro(...$parameters);

    }


    /**
     * @var string
     */
    protected static string $modelClass = Model::class;


    public function model() : string
    {
        return static::$modelClass;
    }

    public function newModel(?Data $data = null) : Model
    {
        return static::$modelClass::make();
    }


    protected function makeHandler($macro)
    {
        if (is_string($macro) && class_exists($macro)) {
            // 反射类  获取 构造函数参数
            $reflection = new ReflectionClass($macro);
            //$parameters = $reflection->getConstructor()->getParameters();
            return app($macro, ['service' => $this]);
        }
        return $macro;
    }
}