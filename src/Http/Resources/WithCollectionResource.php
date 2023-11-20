<?php

namespace RedJasmine\Support\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

trait WithCollectionResource
{


    public static array $commons = [
        'code'    => 0,
        'message' => 'ok'
    ];

    public function with(Request $request) : array
    {
        return self::$commons;
    }

    /**
     * Create a new anonymous resource collection.
     *
     * @param mixed $resource
     * @return AnonymousResourceCollection
     */
    public static function collection($resource) : AnonymousResourceCollection
    {
        return tap(static::newCollection($resource), function ($collection) {
            $collection->additional(self::$commons);
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }


}
