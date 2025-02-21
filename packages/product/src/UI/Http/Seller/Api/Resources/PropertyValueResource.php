<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Property\Models\ProductPropertyValue */
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
            'extras'  => $this->extras,
            'property' => new PropertyResource($this->whenLoaded('property')),
            'group'    => new PropertyGroupResource($this->whenLoaded('group')),

        ];
    }
}
