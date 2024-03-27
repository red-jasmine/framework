<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Foundation\Service\Action;
use RedJasmine\Support\Foundation\Service\HasQueryBuilder;
use RedJasmine\Support\Foundation\Service\ResourceService;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @property ResourceService $service
 */
class QueryAction extends ResourceAction
{

    use HasQueryBuilder;


    protected ?QueryBuilder $query = null;

    /**
     * @param bool $isRequest
     *
     * @return QueryBuilder
     */
    public function execute(bool $isRequest = true) : QueryBuilder
    {
        return $this->query($isRequest);
    }

    public function query(bool $isRequest = false) : QueryBuilder
    {
        return $this->query = $this->query ?? $this->newQuery($isRequest);
    }

    public function newQuery(bool $isRequest = false) : QueryBuilder
    {
        $query = $this->queryBuilder($isRequest);
        return $this->service->callQueryCallbacks($query);
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
