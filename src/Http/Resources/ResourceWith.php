<?php

namespace RedJasmine\Support\Http\Resources;

use Illuminate\Http\Request;

trait ResourceWith
{


    public static array $commons = [
        'code'    => 0,
        'message' => 'ok'
    ];

    public function with(Request $request)
    {
        return self::$commons;
    }

    /**
     * Create a new anonymous resource collection.
     *
     * @param mixed $resource
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        return tap(static::newCollection($resource), function ($collection) {
            $collection->additional(self::$commons);
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }


}
