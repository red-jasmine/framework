<?php

namespace RedJasmine\Support\UI\Http\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Support\Domain\Data\Queries\Query;

/**
 * @property string $treeQueryClass
 */
trait HasTreeAction
{

    public function tree(Request $request)
    {
        $query = isset(static::$treeQueryClass) ? (static::$treeQueryClass::from($request)) : Query::from($request);

        $tree = $this->service->tree($query);

        return static::$resourceClass::collection($tree);
    }

}