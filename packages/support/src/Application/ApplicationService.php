<?php

namespace RedJasmine\Support\Application;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Application\Commands\CreateCommandHandler;
use RedJasmine\Support\Application\Commands\DeleteCommandHandler;
use RedJasmine\Support\Application\Commands\UpdateCommandHandler;
use RedJasmine\Support\Application\Queries\FindQueryHandler;
use RedJasmine\Support\Application\Queries\PaginateQueryHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * @method Model create(Data $command)
 * @method Model update(Data $command)
 * @method bool delete(Data $command)
 * @method Model find(FindQuery $query)
 * @method LengthAwarePaginator|Paginator  paginate(PaginateQuery $query)
 * @property RepositoryInterface $repository
 * @property ReadRepositoryInterface $readRepository
 * @property TransformerInterface $transformer
 */
class ApplicationService extends Service
{

    /**
     * 模型类
     * @var string
     */
    protected static string $modelClass = Model::class;

    /**
     * 获取模型类
     *
     * @return string
     */
    public static function getModelClass() : string
    {
        return self::$modelClass;
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

    protected function makeMacro($macro, $method, $parameters)
    {
        if (is_string($macro) && class_exists($macro)) {
            // 反射类  获取 构造函数参数
            return app($macro, ['service' => $this]);
        }
        return $macro;
    }


    /**
     * @return string
     * @deprecated
     */
    public function model() : string
    {
        return static::$modelClass;
    }

    public function newModel(?Data $data = null) : Model
    {
        return static::$modelClass::make();
    }


}