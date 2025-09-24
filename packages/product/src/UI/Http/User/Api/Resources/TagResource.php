<?php

namespace RedJasmine\Product\UI\Http\User\Api\Resources;

use RedJasmine\Product\Domain\Tag\Models\ProductTag;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductTag
 */
class TagResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'cluster'     => $this->cluster,
            'sort'        => $this->sort,
            'is_leaf'     => $this->is_leaf,
            'is_show'     => $this->is_show,
            'status'      => $this->status,
            'image'       => $this->image,
            'icon'        => $this->icon,
            'color'       => $this->color,
            'extra'       => $this->extra,
        ];
    }

}