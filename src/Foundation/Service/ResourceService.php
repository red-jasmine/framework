<?php

namespace RedJasmine\Support\Foundation\Service;


use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\Foundation\Service\Actions\ResourceAction;
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
    use HasActions {
        HasActions::makeAction as coreMakeAction;
    }


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


    protected function actions() : array
    {
        return [
            'create' => Actions\ResourceCreateAction::class,
            'query'  => Actions\ResourceQueryAction::class,
            'update' => Actions\ResourceUpdateAction::class,
            'delete' => Actions\ResourceDeleteAction::class,
        ];
    }

    protected function makeAction($name) : Action
    {
        // 获取配置信息 TODO
        $actionConfig = $this->mergeActions()[$name];

        /**
         * @var ResourceAction $action
         */

        $action = $this->coreMakeAction($name);
        $action->setValidatorCombiners($actionConfig['validator_combiners'] ?? static::getValidatorCombiners());
        $action->setModelClass($actionConfig['model_class'] ?? static::getModelClass());
        $action->setDataClass($actionConfig['data_class'] ?? static::getDataClass());
        $action->setPipes($actionConfig['pipelines'] ?? static::getPipelines());
        return $action;
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
