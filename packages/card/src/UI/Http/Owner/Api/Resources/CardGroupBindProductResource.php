<?php

namespace RedJasmine\Card\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;


/** @mixin \RedJasmine\Card\Domain\Models\CardGroupBindProduct */
class CardGroupBindProductResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'owner_id'     => $this->owner_id,
            'owner_type'   => $this->owner_type,
            'product_id'   => $this->product_id,
            'product_type' => $this->product_type,
            'sku_id'       => $this->sku_id,
            'group_id'     => $this->group_id,
            'creator_id'   => $this->creator_id,
            'creator_type' => $this->creator_type,
            'updater_id'   => $this->updater_id,
            'updater_type' => $this->updater_type,
            'created_at'   => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'   => $this->updated_at?->format('Y-m-d H:i:s'),
            'group'        => CardGroupResource::make($this->whenLoaded('group')),
        ];
    }
}
