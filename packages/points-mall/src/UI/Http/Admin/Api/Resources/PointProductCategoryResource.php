<?php

namespace RedJasmine\PointsMall\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\PointsMall\Domain\Models\PointsProductCategory;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin PointsProductCategory */
class PointProductCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'description'  => $this->description,
            'image'        => $this->image,
            'cluster'      => $this->cluster,
            'sort'         => $this->sort,
            'is_leaf'      => $this->is_leaf,
            'is_show'      => $this->is_show,
            'children'     => static::collection(collect($this->children)),
            'parent'       => new static($this->whenLoaded('parent')),
        ];
    }
} 