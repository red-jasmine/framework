<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Foundation\Service\Actions;
use RedJasmine\Support\Foundation\Service\HasQueryBuilder;
use RedJasmine\Support\Foundation\Service\ResourceService;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @property ResourceService $service
 */
class ResourceQueryAction extends Actions
{

    use HasQueryBuilder;


    /**
     * @return QueryBuilder
     */
    public function execute() : QueryBuilder
    {
        return $this->query();
    }

    protected ?QueryBuilder $query = null;


    public function query() : QueryBuilder
    {
        if (!$this->query) {
            $this->query = $this->queryBuilder();
        }
        return $this->service->callQueryCallbacks($this->query);
    }


    /**
     * @return Collection|array
     */
    public function get() : Collection|array
    {
        return $this->query()->get();
    }

    public function find($id) : ?Model
    {
        return $this->query()->find($id);
    }

    public function findOrFail($id) : Model
    {
        return $this->query()->findOrFail($id);
    }

    public function paginate() : LengthAwarePaginator
    {
        return $this->query->paginate();
    }

    public function simplePaginate() : LengthAwarePaginator
    {
        return $this->query->simplePaginate();
    }

    public function tree(array $nodes = null) : array
    {
        if ($nodes === null) {
            $nodes = $this->query()->get();
        }

        return static::buildNestedArray($nodes,);
    }

    public static function buildNestedArray(
        $nodes = [],
        $parentId = 0,
        ?string $primaryKeyName = null,
        ?string $parentKeyName = null,
        ?string $childrenKeyName = null
    ) : array
    {
        $branch          = [];
        $primaryKeyName  = $primaryKeyName ?: 'id';
        $parentKeyName   = $parentKeyName ?: 'parent_id';
        $childrenKeyName = $childrenKeyName ?: 'children';

        $parentId = is_numeric($parentId) ? (int)$parentId : $parentId;

        foreach ($nodes as $node) {
            $pk = $node[$parentKeyName];
            $pk = is_numeric($pk) ? (int)$pk : $pk;

            if ($pk === $parentId) {
                $children = static::buildNestedArray(
                    $nodes,
                    $node[$primaryKeyName],
                    $primaryKeyName,
                    $parentKeyName,
                    $childrenKeyName
                );

                if ($children) {
                    $node[$childrenKeyName] = $children;
                }
                $branch[] = $node;
            }
        }

        return $branch;
    }

}
