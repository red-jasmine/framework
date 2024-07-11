<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Property\Models\ProductProperty */
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
            'expands'  => $this->expands,
            'group_id' => $this->group_id,
            'group'    => new PropertyGroupResource($this->whenLoaded('group')),
        ];
    }
}
