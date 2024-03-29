<?php

namespace RedJasmine\Support\Foundation\Service;


use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Foundation\Service\Actions\ResourceAction;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @property Actions\QueryAction  $query
 * @property Actions\CreateAction $create
 * @property Actions\UpdateAction $update
 * @property Actions\DeleteAction $delete
 * @method  QueryBuilder query(bool $isRequest = false)
 * @method  Model create(Data|array $data)
 * @method  Model update(int $id, Data|array $data)
 * @method  bool delete(int $id)
 * @method  bool forceDelete(int $id)
 * @method  bool restore(int $id)
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

    public function getQueryCallbacks() : array
    {
        return $this->queryCallbacks;
    }

    public function setQueryCallbacks(array $queryCallbacks) : ResourceService
    {
        $this->queryCallbacks = $queryCallbacks;
        return $this;
    }



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
        'create' => Actions\CreateAction::class,
        'query'  => Actions\QueryAction::class,
        'update' => Actions\UpdateAction::class,
        'delete' => Actions\DeleteAction::class,
    ];

    protected function initializeAction($action, array $config = []) : void
    {
        parent::initializeAction($action, $config);


        /**
         * @var ResourceAction $action
         */
        $action->addValidatorCombiner(static::getValidatorCombiners());
        $action->addPipe(static::getPipelines());

        if (isset($config['validator_combiners'])) {
            $action->setValidatorCombiners($config['validator_combiners'] ?? []);
        }
        if (isset($config['pipelines'])) {
            $action->setPipes($config['pipelines'] ?? []);
        }
        if (!$action->getModelClass()){
            $action->setModelClass($config['model_class'] ?? static::getModelClass());
        }
        if (!$action->getDataClass()){
            $action->setDataClass($config['data_class'] ?? static::getDataClass());
        }


        if(method_exists($action,'setFilters')){

            $action->setFilters($config['filters']??static::filters());
        }
        if(method_exists($action,'setFields')){
            $action->setFields($config['fields']??[]);
        }
        if(method_exists($action,'setIncludes')){
            $action->setIncludes($config['includes']??[]);
        }
        if(method_exists($action,'setSorts') && isset($config['sorts'])){
            $action->setSorts($config['sorts']);
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
