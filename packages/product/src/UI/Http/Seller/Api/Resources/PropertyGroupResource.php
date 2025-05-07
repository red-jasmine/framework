<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Property\Models\ProductPropertyGroup */
class PropertyGroupResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'sort'         => $this->sort,
            'status'       => $this->status,
            'extra'      => $this->extra,
        ];
    }
}
