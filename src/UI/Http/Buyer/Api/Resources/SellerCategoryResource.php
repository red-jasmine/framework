<?php

namespace RedJasmine\Product\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Category\Models\ProductSellerCategory */
class SellerCategoryResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'         => $this->id,
            'parent_id'  => $this->parent_id,
            'owner_id'   => $this->owner_id,
            'owner_type' => $this->owner_type,
            'name'       => $this->name,
            'group_name' => $this->group_name,
            'image'      => $this->image,
            'sort'       => $this->sort,
            'is_leaf'    => $this->is_leaf,
            'is_show'    => $this->is_show,
            'status'     => $this->status,
            'expands'    => $this->expands,
            'children'   => static::collection(collect($this->children)),
            'parent'     => new static($this->whenLoaded('parent')),
        ];
    }
}
