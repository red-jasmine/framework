<?php

namespace RedJasmine\Product\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Attribute\Models\ProductAttributeValue */
class PropertyValueResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'       => $this->id,
            'pid'      => $this->pid,
            'group_id' => $this->group_id,
            'name'     => $this->name,
            'sort'     => $this->sort,
            'status'   => $this->status,
            'extra'  => $this->extra,
            'property' => new PropertyResource($this->whenLoaded('property')),
            'group'    => new PropertyGroupResource($this->whenLoaded('group')),

        ];
    }
}
