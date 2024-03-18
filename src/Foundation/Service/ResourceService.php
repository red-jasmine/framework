<?php

namespace RedJasmine\Support\Foundation\Service;


use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Services\Brand\Data\BrandData;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @property Actions\ResourceQueryAction  $query
 * @property Actions\ResourceCreateAction $create
 * @property Actions\ResourceUpdateAction $update
 * @property Actions\ResourceDeleteAction $delete
 * @method  QueryBuilder query()
 * @method  Model create(BrandData|array $data)
 * @method  Model update(int $id, BrandData|array $data)
 * @method  bool delete(int $id)
 */
class ResourceService extends Service
{


    protected array $queryCallbacks = [];

    public function withQuery(\Closure $query = null) : static
    {
        $this->queryCallbacks[] = $query;

        return $this;
    }

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

}
