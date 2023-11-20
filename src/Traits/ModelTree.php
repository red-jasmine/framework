<?php

namespace RedJasmine\Support\Traits;

use Closure;

trait ModelTree
{

    protected array $queryCallbacks = [];

    public function withQuery(Closure $query = null) : static
    {
        $this->queryCallbacks[] = $query;

        return $this;
    }


    public function getParentColumn() : string
    {
        return property_exists($this, 'parentColumn') ? $this->parentColumn : 'parent_id';
    }

    /**
     * Get title column.
     *
     * @return string
     */
    public function getTitleColumn() : string
    {
        return property_exists($this, 'titleColumn') ? $this->titleColumn : 'title';
    }

    /**
     * Get order column name.
     *
     * @return string
     */
    public function getOrderColumn() : string
    {
        return property_exists($this, 'orderColumn') ? $this->orderColumn : 'order';
    }

    /**
     * Get depth column name.
     *
     * @return string
     */
    public function getDepthColumn() : string
    {
        return property_exists($this, 'depthColumn') ? $this->depthColumn : '';
    }

    /**
     * @return string
     */
    public function getDefaultParentId() : string
    {
        return property_exists($this, 'defaultParentId') ? $this->defaultParentId : '0';
    }


    public function toTree(array $nodes = null)
    {
        if ($nodes === null) {
            $nodes = $this->allNodes();
        }

        return static::buildNestedArray(
            $nodes,
            $this->getDefaultParentId(),
            $this->getKeyName(),
            $this->getParentColumn()
        );
    }

    protected function callQueryCallbacks($model)
    {
        foreach ($this->queryCallbacks as $callback) {
            if ($callback) {
                $model = $callback($model);
            }
        }

        return $model;
    }

    public function allNodes()
    {
        return $this->callQueryCallbacks(new static())
                    ->orderBy($this->getOrderColumn(), 'asc')
                    ->get();
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
