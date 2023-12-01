<?php

namespace RedJasmine\Support\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;

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
     *
     * @return AnonymousResourceCollection
     */
    public static function collection($resource) : AnonymousResourceCollection
    {

        return tap(static::newCollection($resource), function ($collection) {
            /**
             * @var $collection AnonymousResourceCollection
             */
            $collection->additional(self::$commons);
            AnonymousResourceCollection::macro('paginationInformation', function ($request, $paginated, $default) {
                return [ 'mate' => Arr::except($paginated, [
                    'data',
                    'first_page_url',
                    'last_page_url',
                    'prev_page_url',
                    'next_page_url',
                    'links',
                ]) ];
            });
            $collection->preserveQuery();

            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }


}
