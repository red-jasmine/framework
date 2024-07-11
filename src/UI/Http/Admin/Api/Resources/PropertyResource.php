<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Property\Models\ProductProperty */
class PropertyResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'type'         => $this->type,
            'name'         => $this->name,
            'unit'         => $this->unit,
            'sort'         => $this->sort,
            'status'       => $this->status,
            'expands'      => $this->expands,
            'creator_id'   => $this->creator_id,
            'creator_type' => $this->creator_type,
            'updater_id'   => $this->updater_id,
            'updater_type' => $this->updater_type,
            'created_at'   => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'   => $this->updated_at?->format('Y-m-d H:i:s'),
            'group_id'     => $this->group_id,
            'group'        => new PropertyGroupResource($this->whenLoaded('group')),

        ];
    }
}
