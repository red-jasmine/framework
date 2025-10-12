<?php

namespace RedJasmine\Product\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Attribute\Models\ProductAttribute */
class PropertyResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'       => $this->id,
            'type'     => $this->type,
            'name'     => $this->name,
            'unit'     => $this->unit,
            'sort'     => $this->sort,
            'status'   => $this->status,
            'extra'  => $this->extra,
            'group_id' => $this->group_id,
            'group'    => new PropertyGroupResource($this->whenLoaded('group')),
        ];
    }
}
