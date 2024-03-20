<?php

namespace RedJasmine\Support\Foundation\Service;


use Closure;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\DataTransferObjects\Data;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @property Actions\ResourceQueryAction  $query
 * @property Actions\ResourceCreateAction $create
 * @property Actions\ResourceUpdateAction $update
 * @property Actions\ResourceDeleteAction $delete
 * @method  QueryBuilder query()
 * @method  Model create(Data|array $data)
 * @method  Model update(int $id, Data|array $data)
 * @method  bool delete(int $id)
 */
class ResourceService extends Service
{

    /**
     * 资源模型
     * @var string
     */
    protected static string $model = Model::class;

    /**
     * 值对象
     * @var string
     */
    protected static string $dataClass = Data::class;

    /**
     * 验证管理器
     * @var string|null
     */
    protected static ?string $validatorManageClass = null;

    /**
     * 有所属人
     * @var bool
     */
    public static bool $autoModelWithOwner = false;

    /**
     * 所属人 前缀
     * @var string
     */
    public static string $modelOwnerKey = 'owner';

    /**
     * @return string|null|Model
     */
    public static function getModel() : ?string
    {
        return static::$model;
    }

    public static function getDataClass() : ?string
    {
        return static::$dataClass;
    }

    public static function getValidatorManageClass() : ?string
    {
        return static::$validatorManageClass;
    }


    protected array $queryCallbacks = [];

    public function withQuery(Closure $query = null) : static
    {
        $this->queryCallbacks[] = $query;

        return $this;
    }

    /**
     * @param $query
     *
     * @return QueryBuilder
     */
    public function callQueryCallbacks($query)
    {
        foreach ($this->queryCallbacks as $callback) {
            if ($callback) {
                $callback($query);
            }
        }
        return $query;
    }

    // TODO 修改为方法 支持重写
    // 当前最为包发布的时候 支持配置
    // 如果一个包 支持别人扩展时 支持扩展
    protected static array $actions = [
        'create' => Actions\ResourceCreateAction::class,
        'query'  => Actions\ResourceQueryAction::class,
        'update' => Actions\ResourceUpdateAction::class,
        'delete' => Actions\ResourceDeleteAction::class,
    ];


    public static function filters() : array
    {
        return [];
    }

    public static function sorts() : array
    {
        return [];
    }

    public static function includes() : array
    {
        return [];
    }

    public static function fields() : array
    {
        return [];
    }


}
