<?php

namespace RedJasmine\Support\UI\Http\Resources\Json;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JsonResource extends \Illuminate\Http\Resources\Json\JsonResource
{


    public static function collection($resource) : AnonymousResourceCollection
    {
        $collection       = parent::collection($resource);
        $collection->with = [
            'code'    => 0,
            'message' => 'ok'
        ];
        return $collection;
    }

    public $with = [
        'code'    => 0,
        'message' => 'ok'
    ];


}
