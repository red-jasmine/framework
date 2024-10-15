<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;
use RedJasmine\Product\Domain\Tag\Models\ProductTag;

/** @mixin ProductTag */
class ProductTagResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'icon'        => $this->icon,
            'color'       => $this->color,
            'is_show'     => $this->is_show,
            'status'      => $this->status,
            'sort'        => $this->sort,
            'is_public'   => $this->is_public,
        ];
    }
}
