<?php

namespace RedJasmine\Support\Domain\Models\Traits;

use Closure;

/**
 * @property  $children
 */
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

    /**
     * Get options for Select field in form.
     *
     * @param Closure|null $closure
     * @param string       $rootText
     *
     * @return array
     */
    public static function selectOptions(Closure $closure = null,$needRoot= true, $rootText = null)
    {
        $rootText = $rootText ?: '顶级';

        $options = (new static())->withQuery($closure)->buildSelectOptions();
        if($needRoot){
            return collect($options)->prepend($rootText, 0)->all();
        }
        return collect($options)->all();
    }

    /**
     * Build options of select field in form.
     *
     * @param  array  $nodes
     * @param  int  $parentId
     * @param  string  $prefix
     * @param  string  $space
     * @return array
     */
    protected function buildSelectOptions(array $nodes = [], $parentId = 0, $prefix = '', $space = '&nbsp;')
    {
        $d = '├─';
        $prefix = $prefix ?: $d.$space;

        $options = [];

        if (empty($nodes)) {
            $nodes = $this->allNodes()->toArray();
        }

        foreach ($nodes as $index => $node) {
            if ($node[$this->getParentColumn()] == $parentId) {
                $currentPrefix = $this->hasNextSibling($nodes, $node[$this->getParentColumn()], $index) ? $prefix : str_replace($d, '└─', $prefix);

                $node[$this->getTitleColumn()] = $currentPrefix.$space.$node[$this->getTitleColumn()];

                $childrenPrefix = str_replace($d, str_repeat($space, 6), $prefix).$d.str_replace([$d, $space], '', $prefix);

                $children = $this->buildSelectOptions($nodes, $node[$this->getKeyName()], $childrenPrefix);

                $options[$node[$this->getKeyName()]] = $node[$this->getTitleColumn()];

                if ($children) {
                    $options += $children;
                }
            }
        }

        return $options;
    }

    protected function hasNextSibling($nodes, $parentId, $index)
    {
        foreach ($nodes as $i => $node) {
            if ($node[$this->getParentColumn()] == $parentId && $i > $index) {
                return true;
            }
        }
    }


}
