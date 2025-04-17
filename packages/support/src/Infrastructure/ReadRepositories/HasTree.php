<?php

namespace RedJasmine\Support\Infrastructure\ReadRepositories;

use RedJasmine\Support\Domain\Data\Queries\Query;

trait HasTree
{

    public function tree(?Query $query = null) : array
    {
        $nodes = $this->query($query)->get();
        $model = (new static::$modelClass);

        return $model->toTree($nodes);
    }

    public static function buildNestedArray(
        $nodes = [],
        $parentId = 0,
        ?string $primaryKeyName = null,
        ?string $parentKeyName = null,
        ?string $childrenKeyName = null
    ) : array {
        $branch          = [];
        $primaryKeyName  = $primaryKeyName ?: 'id';
        $parentKeyName   = $parentKeyName ?: 'parent_id';
        $childrenKeyName = $childrenKeyName ?: 'children';

        $parentId = is_numeric($parentId) ? (int) $parentId : $parentId;

        foreach ($nodes as $node) {
            $pk = $node[$parentKeyName];
            $pk = is_numeric($pk) ? (int) $pk : $pk;

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
