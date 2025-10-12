<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Attribute\Models\ProductAttribute */
class AttributeResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'                => $this->id,
            'type'              => $this->type,
            'is_allow_multiple' => $this->is_allow_multiple,
            'is_allow_alias'    => $this->is_allow_alias,
            'name'              => $this->name,
            'unit'              => $this->unit,
            'sort'              => $this->sort,
            'status'            => $this->status,
            'creator_id'        => $this->creator_id,
            'creator_type'      => $this->creator_type,
            'updater_id'        => $this->updater_id,
            'updater_type'      => $this->updater_type,
            'created_at'        => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'        => $this->updated_at?->format('Y-m-d H:i:s'),
            'group_id'          => $this->group_id,
            'group'             => new AttributeGroupResource($this->whenLoaded('group')),

        ];
    }
}
