<?php

namespace RedJasmine\PointsMall\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\PointsMall\Domain\Models\PointsProductCategory;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin PointsProductCategory */
class PointProductCategoryResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'          => $this->id,
            'parent_id'   => $this->parent_id,
            'name'        => $this->name,
            'description' => $this->description,
            'image'       => $this->image,
            'sort'        => $this->sort,
            'is_show'     => $this->is_show,
            'children'    => static::collection(collect($this->whenLoaded('children'))),
        ];
    }
}