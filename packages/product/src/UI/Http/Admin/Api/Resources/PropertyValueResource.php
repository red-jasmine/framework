<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Property\Models\ProductPropertyValue */
class PropertyValueResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'pid'          => $this->pid,
            'group_id'     => $this->group_id,
            'name'         => $this->name,
            'sort'         => $this->sort,
            'status'       => $this->status,
            'extras'      => $this->extras,
            'creator_id'   => $this->creator_id,
            'creator_type' => $this->creator_type,
            'updater_id'   => $this->updater_id,
            'updater_type' => $this->updater_type,
            'created_at'   => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'   => $this->updated_at?->format('Y-m-d H:i:s'),
            'property'     => new PropertyResource($this->whenLoaded('property')),
            'group'        => new PropertyGroupResource($this->whenLoaded('group')),

        ];
    }
}
