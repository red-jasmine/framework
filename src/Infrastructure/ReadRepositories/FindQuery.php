<?php

namespace RedJasmine\Support\Infrastructure\ReadRepositories;

use Illuminate\Http\Request;
use RedJasmine\Support\Data\Data;

class FindQuery extends Data
{
    public mixed $include;

    public mixed $fields;

    public mixed $append;

    public mixed $id;


    public static function fromRequestRoute(Request $request, $id) : static
    {
        $request->offsetSet('id', $id);
        return static::from($request);
    }

}
