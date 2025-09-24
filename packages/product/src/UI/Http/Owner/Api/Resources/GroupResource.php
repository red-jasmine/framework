<?php

namespace RedJasmine\Product\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Group\Models\ProductGroup */
class GroupResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'owner_id'     => $this->owner_id,
            'owner_type'   => $this->owner_type,
            'name'         => $this->name,
            'group_name'   => $this->group_name,
            'image'        => $this->image,
            'sort'         => $this->sort,
            'is_leaf'      => $this->is_leaf,
            'is_show'      => $this->is_show,
            'status'       => $this->status,
            'extra'      => $this->extra,
            'creator_id'   => $this->creator_id,
            'creator_type' => $this->creator_type,
            'updater_id'   => $this->updater_id,
            'updater_type' => $this->updater_type,
            'created_at'   => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'   => $this->updated_at?->format('Y-m-d H:i:s'),
            'children'     => static::collection(collect($this->children)),
            'parent_id'    => $this->parent_id,
            'parent'       => new static($this->whenLoaded('parent')),
        ];
    }
}
