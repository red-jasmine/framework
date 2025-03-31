<?php

namespace RedJasmine\Support\UI\Http\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Support\Domain\Data\Queries\Query;

trait HasTreeAction
{

    public function tree(Request $request)
    {
        $tree = $this->service->tree(Query::from($request));
        return static::$resourceClass::collection($tree);
    }

}