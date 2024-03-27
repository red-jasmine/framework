<?php

namespace RedJasmine\Support\Foundation\Service;


use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\Foundation\Service\Actions\ResourceAction;
use RedJasmine\Support\Foundation\Service\Actions\ResourceQueryAction;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @property Actions\ResourceQueryAction  $query
 * @property Actions\ResourceCreateAction $create
 * @property Actions\ResourceUpdateAction $update
 * @property Actions\ResourceDeleteAction $delete
 * @method  QueryBuilder query(bool $isRequest = true)
 * @method  Model create(Data|array $data)
 * @method  Model update(int $id, Data|array $data)
 * @method  bool delete(int $id)
 */
class ResourceService extends Service
{
    /**
     * 所属人
     * @var bool
     */
    public static bool $autoModelWithOwner = false;
    /**
     * 所属人 前缀
     * @var string
     */
    public static string $modelOwnerKey = 'owner';
    /**
     * 资源模型
     * @var string
     */
    protected static string $modelClass = Model::class;
    /**
     * 值对象
     * @var string
     */
    protected static string $dataClass = Data::class;
    /**
     * 验证组合器
     * @var array
     */
    protected static array $validatorCombiners = [];

    /**
     * 默认管道
     * @var array
     */
    protected static array $pipelines = [];

    /**
     * @var array
     */
    protected array $queryCallbacks = [];

    public function withQuery(Closure $query = null) : static
    {
        $this->queryCallbacks[] = $query;

        return $this;
    }

    /**
     * @param $query
     *
     * @return QueryBuilder|Builder
     */
    public function callQueryCallbacks($query) : QueryBuilder|Builder
    {
        foreach ($this->queryCallbacks as $callback) {
            if ($callback) {
                $callback($query);
            }
        }
        return $query;
    }


    protected array $actions = [
        'create' => Actions\ResourceCreateAction::class,
        'query'  => Actions\ResourceQueryAction::class,
        'update' => Actions\ResourceUpdateAction::class,
        'delete' => Actions\ResourceDeleteAction::class,
    ];

    protected function initializeAction($action, array $config = []) : void
    {
        parent::initializeAction($action, $config);
        $action->setValidatorCombiners($config['validator_combiners'] ?? static::getValidatorCombiners());
        $action->setModelClass($config['model_class'] ?? static::getModelClass());
        $action->setDataClass($config['data_class'] ?? static::getDataClass());
        $action->setPipes($config['pipelines'] ?? static::getPipelines());

        if (method_exists($action, 'filters')) {
            $action->setFilters($config['filters'] ?? []);
        }
        if (method_exists($action, 'fields')) {
            $action->setFields($config['fields'] ?? []);
        }
        if (method_exists($action, 'includes')) {
            $action->setIncludes($config['includes'] ?? []);
        }
        if (method_exists($action, 'sorts')) {
            $action->setSorts($config['sorts'] ?? []);
        }

    }

    public static function getValidatorCombiners() : array
    {
        return static::$validatorCombiners;
    }

    public static function setValidatorCombiners(array $validatorCombiners) : void
    {
        static::$validatorCombiners = $validatorCombiners;
    }

    /**
     * @return string|null
     */
    public static function getModelClass() : ?string
    {
        return static::$modelClass;
    }

    public static function getDataClass() : ?string
    {
        return static::$dataClass;
    }

    public static function getPipelines() : array
    {
        return static::$pipelines;
    }

    public static function setPipelines(array $pipelines) : void
    {
        static::$pipelines = $pipelines;
    }


}
